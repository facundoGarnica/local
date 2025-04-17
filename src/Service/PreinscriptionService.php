<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use League\Csv\Reader;
use League\Csv\Writer;

class PreinscriptionService
{
    private $externalConnection;

    public function __construct(Connection $externalConnection)
    {
        $this->externalConnection = $externalConnection;
    }

    public function fetchTables(): array
    {
        $sql = 'SHOW TABLES';
        return $this->externalConnection->fetchAllAssociative($sql);
    }

    public function fetchTableData(string $tableName): array
    {
        $sql = sprintf('SELECT * FROM %s', $tableName);
        return $this->externalConnection->fetchAllAssociative($sql);
    }

    public function generateCsv(array $data, string $tableName): string
    {
        $directory = '../public/preinscriptos';
        $filename = sprintf('%s/%s.csv', $directory, $tableName);
        
        $file = fopen($filename, 'w');
    
        if (!empty($data)) {
            // Write the header
            fputcsv($file, array_keys($data[0]));
    
            // Write the data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
        }
    
        fclose($file);
    
        return $filename;
    }

    public function generateAllCsv(): array
    {
        $tables = $this->fetchTables();
        $files = [];

        foreach ($tables as $table) {
            $tableName = $table['Tables_in_region']; 
            $data = $this->fetchTableData($tableName);
            $filename = $this->generateCsv($data, $tableName);
            $files[] = $filename;
        }

        return $files;
    }

    /*POR NUMERO DE CUE*/ 
    public function fetchPreinscriptionDataByInstitute(string $instituteId): array
    {
        $sql = '
            SELECT 
                -- Datos de alumno
                a.titulo_sec, 
                a.escuela_sec, 
                a.anio_egreso, 
                -- Datos de persona
                per.nombre AS persona_nombre, 
                per.apellido AS persona_apellido, 
                per.fecha_nacimiento, 
                per.dni_pasaporte, 
                per.genero, 
                per.email, 
                per.telefono, 
                per.partido, 
                per.calle, 
                per.numero, 
                per.piso, 
                per.departamento, 
                per.pasillo, 
                -- Datos de pais
                pais.descripcion AS pais_descripcion, 
                -- Datos de localidad
                loc.nombre AS localidad_nombre, 
                loc.codigo_postal, 
                loc.distrito AS localidad_distrito, 
                -- Datos de provincia
                prov.descripcion AS provincia_descripcion, 
                -- Datos de region
                reg.numero AS region_numero,
                -- Datos de oferta educativa
                oe.ciclo_lectivo AS oferta_ciclo, 
                -- Datos de tecnicatura
                t.nombre AS tecnicatura_nombre, 
                t.numero_resolucion AS tecnicatura_resolucion,
                -- Datos de turno
                tu.descripcion AS turno_descripcion, 
                -- Datos de instituto
                i.numero AS instituto_numero,
                i.numero_cue AS instituto_cue
            FROM 
                preinscripcion p 
            JOIN 
                alumno a ON p.alumno_id = a.id 
            JOIN 
                persona per ON a.persona_id = per.id 
            JOIN 
                oferta_educativa oe ON p.oferta_educativa_id = oe.id 
            JOIN 
                tecnicatura t ON oe.tecnicatura_id = t.id 
            JOIN 
                turno tu ON oe.turno_id = tu.id 
            JOIN 
                instituto i ON oe.instituto_id = i.id 
            JOIN 
                pais pais ON per.pais_id = pais.id 
            JOIN 
                localidad loc ON per.localidad_id = loc.id 
            JOIN 
                provincia prov ON loc.provincia_id = prov.id 
            JOIN 
                region reg ON loc.region_id = reg.id 
            WHERE 
                i.numero_cue = :instituteId
        ';
    
        $result = $this->externalConnection->fetchAllAssociative($sql, ['instituteId' => $instituteId]);
        
        // Verificar si hay resultados
        if (empty($result)) {
            return []; // Retornar un array vacío si no hay datos
        }
        
        return $result;
    }


    public function readCsv(): array
    {
        $filePath = '../public/preinscriptos/preinscripciones_instituto.csv';
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
    
        $records = iterator_to_array($csv->getRecords());
    
        return $records;
    }

    public function readCsvCopy(): array
    {
        $filePath = '../public/preinscriptos/preinscripciones_instituto_copy.csv';
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $records = iterator_to_array($csv->getRecords());
        
        // Opcional: Verificar que el campo 'id' esté presente
        foreach ($records as &$record) {
            if (!isset($record['id'])) {
                $record['id'] = null; 
            }
        }

        return $records;
    }
    
    

    public function generateFilteredCsv(int $instituteId): string
    {
        $data = $this->fetchPreinscriptionDataByInstitute($instituteId);
        return $this->generateCsv($data, 'preinscripciones_instituto');
    }

/*
    public function copyAndAddField(string $filePath): string
    {
        // Abrir el archivo CSV original
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        // Crear o abrir el archivo CSV copiado
        $newFilePath = str_replace('.csv', '_copy.csv', $filePath);
        $writer = Writer::createFromPath($newFilePath, file_exists($newFilePath) ? 'a+' : 'w+'); // 'a+' para agregar al final si existe

        // Leer los registros existentes en el archivo copiado
        $existingRecords = [];
        if (file_exists($newFilePath) && filesize($newFilePath) > 0) {  // Verificar si el archivo no está vacío
            $existingCsv = Reader::createFromPath($newFilePath, 'r');
            $existingCsv->setHeaderOffset(0);
            foreach ($existingCsv->getRecords() as $record) {
                $existingRecords[$record['dni_pasaporte']] = $record; // Usar el DNI como clave
            }
        }

        // Leer los registros del archivo original
        $header = $csv->getHeader();
        if (filesize($newFilePath) === 0) {  // Agregar los encabezados solo si el archivo copiado está vacío
            $header[] = 'id';        // Añadir el campo ID
            $header[] = 'new_field'; // Añadir el nuevo campo
            $writer->insertOne($header);
        }

        $id = count($existingRecords) + 1; // Continuar desde el último ID existente

        foreach ($csv->getRecords() as $record) {
            $dni = $record['dni_pasaporte']; // Asegúrate de que tienes un campo 'dni_pasaporte' en los registros

            // Verificar si el registro ya existe en el archivo copiado
            if (!isset($existingRecords[$dni])) {
                // Si no existe, agregarlo con los nuevos campos
                $record['id'] = $id;
                $record['new_field'] = '0'; // Valor booleano predeterminado
                $writer->insertOne($record);
                $id++; // Incrementar el ID para el siguiente registro
            }
        }

        return $newFilePath;
    }
*/
public function copyAndAddField(string $filePath): string
{
    // Verificar si el archivo original existe y no está vacío
    if (!file_exists($filePath) || filesize($filePath) === 0) {
        throw new \RuntimeException("El archivo original no existe o está vacío.");
    }

    // Abrir el archivo CSV original
    $csv = Reader::createFromPath($filePath, 'r');
    $csv->setHeaderOffset(0);

    // Crear o abrir el archivo CSV copiado
    $newFilePath = str_replace('.csv', '_copy.csv', $filePath);
    $writer = Writer::createFromPath($newFilePath, file_exists($newFilePath) ? 'a+' : 'w+'); // 'a+' para agregar al final si existe

    // Leer los registros existentes en el archivo copiado
    $existingRecords = [];
    if (file_exists($newFilePath) && filesize($newFilePath) > 0) {  // Verificar si el archivo no está vacío
        $existingCsv = Reader::createFromPath($newFilePath, 'r');
        $existingCsv->setHeaderOffset(0);
        foreach ($existingCsv->getRecords() as $record) {
            $existingRecords[$record['dni_pasaporte']] = $record; // Usar el DNI como clave
        }
    }

    // Leer los registros del archivo original
    $header = $csv->getHeader();
    if (filesize($newFilePath) === 0) {  // Agregar los encabezados solo si el archivo copiado está vacío
        $header[] = 'id';        // Añadir el campo ID
        $header[] = 'new_field'; // Añadir el nuevo campo
        $writer->insertOne($header);
    }

    $id = count($existingRecords) + 1; // Continuar desde el último ID existente

    foreach ($csv->getRecords() as $record) {
        $dni = $record['dni_pasaporte']; // Asegúrate de que tienes un campo 'dni_pasaporte' en los registros

        // Verificar si el registro ya existe en el archivo copiado
        if (!isset($existingRecords[$dni])) {
            // Si no existe, agregarlo con los nuevos campos
            $record['id'] = $id;
            $record['new_field'] = '0'; // Valor booleano predeterminado
            $writer->insertOne($record);
            $id++; // Incrementar el ID para el siguiente registro
        }
    }

    return $newFilePath;
}






    public function updateCsv(string $filePath, array $data): void
    {
        // Leer los registros existentes del archivo CSV
        $existingData = [];
        $csvReader = Reader::createFromPath($filePath, 'r');
        $csvReader->setHeaderOffset(0); // La primera fila contiene los encabezados
        $header = $csvReader->getHeader();
        
        // Leer todos los registros existentes
        foreach ($csvReader->getRecords() as $record) {
            $existingData[$record['id']] = $record; // Usar 'id' como clave para acceso rápido
        }
    
        // Actualizar los registros con los nuevos datos
        foreach ($data as $record) {
            if (isset($record['id']) && isset($existingData[$record['id']])) {
                // Solo actualizar los campos presentes en $record
                $existingData[$record['id']] = array_merge($existingData[$record['id']], $record);
            }
        }
    
        // Escribir los registros actualizados de vuelta al archivo CSV
        $csvWriter = Writer::createFromPath($filePath, 'w+');
        $csvWriter->insertOne($header); // Inserta la cabecera
        
        // Escribir todos los registros actualizados
        foreach ($existingData as $updatedRecord) {
            $csvWriter->insertOne($updatedRecord);
        }
    }


    public function updateCsvByDni(string $filePath, array $data): void
    {
        // Leer los registros existentes del archivo CSV
        $existingData = [];
        $csvReader = Reader::createFromPath($filePath, 'r');
        $csvReader->setHeaderOffset(0); // La primera fila contiene los encabezados
        $header = $csvReader->getHeader();
        
        // Leer todos los registros existentes
        foreach ($csvReader->getRecords() as $record) {
            $existingData[$record['dni_pasaporte']] = $record; // Usar 'dni_pasaporte' como clave
        }

        // Actualizar los registros con los nuevos datos
        foreach ($data as $record) {
            if (isset($record['dni_pasaporte']) && isset($existingData[$record['dni_pasaporte']])) {
                // Solo actualizar los campos presentes en $record
                $existingData[$record['dni_pasaporte']] = array_merge($existingData[$record['dni_pasaporte']], $record);
            }
        }

        // Escribir los registros actualizados de vuelta al archivo CSV
        $csvWriter = Writer::createFromPath($filePath, 'w+');
        $csvWriter->insertOne($header); // Inserta la cabecera
        
        // Escribir todos los registros actualizados
        foreach ($existingData as $updatedRecord) {
            $csvWriter->insertOne($updatedRecord);
        }
    }

    

}
