<?php

require_once __DIR__ . '/../config.php';

class Medicine
{
    public function findById($id)
    {
        return OneFetch('SELECT * FROM medicines WHERE id = ?', [$id]);
    }
    
    public function findAll()
    {
        return AllFetch('SELECT * FROM medicines ORDER BY name');
    }
    
    public function create($data)
    {
        return insertRow('medicines', $data);
    }
    
    public function update($id, $data)
    {
        unset($data['id']);
        return update('medicines', $data, 'id', $id);
    }
    
    public function delete($id)
    {
        return deleteRow('medicines', 'id', $id);
    }
}