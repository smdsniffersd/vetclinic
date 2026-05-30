<?php

require_once __DIR__ . '/../config.php';

class Pet
{
    public function findById($id)
    {
        return OneFetch('SELECT * FROM pets WHERE id = ?', [$id]);
    }
    
    public function getByOwnerId($ownerId)
    {
        return AllFetch('SELECT * FROM pets WHERE owner_id = ?', [$ownerId]);
    }
    
    public function findByNameAndOwner($name, $ownerId)
    {
        return OneFetch('SELECT * FROM pets WHERE name = ? AND owner_id = ?', [$name, $ownerId]);
    }
    
    public function create($data)
    {
        $petData = [
            'owner_id' => $data['owner_id'],
            'name' => $data['name'],
            'view' => $data['view'] ?? null,
            'Breed' => $data['breed'] ?? null,
            'Age' => $data['age'] ?? null,
            'weight' => $data['weight'] ?? null,
            'special_marks' => $data['special_marks'] ?? null
        ];
        
        return insertRow('pets', $petData);
    }
}