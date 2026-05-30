<?php
require_once __DIR__ . '/../config.php';

class Service
{

    public function findById($id)
    {
        return OneFetch('SELECT * FROM services WHERE id = ?', [$id]);
    }
    
    public function findAll()
    {
        return AllFetch('SELECT * FROM services');
    }
    
    public function getByDoctorType($doctorTypeId)
    {
        return AllFetch('SELECT * FROM services WHERE doctor_type_id = ?', [$doctorTypeId]);
    }

    public function create($data)
    {
        return insertRow('services', $data);
    }
    

    public function update($id, $data)
    {
        unset($data['id']);
        return update('services', $data, 'id', $id);
    }
    

    public function delete($id)
    {
        return deleteRow('services', 'id', $id);
    }
}