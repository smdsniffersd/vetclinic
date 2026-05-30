<?php

require_once __DIR__ . '/../config.php';

class Personal
{

    public function findById($id)
    {
        return OneFetch('SELECT * FROM personal WHERE id = ?', [$id]);
    }
    
    public function findAll()
    {
        return AllFetch(
            'SELECT p.*, pr.name as profession_name 
             FROM personal p
             LEFT JOIN professions pr ON pr.id = p.profession_id'
        );
    }
    
    public function getDoctors()
    {
        return AllFetch(
            'SELECT p.*, pr.name as profession_name 
             FROM personal p
             LEFT JOIN professions pr ON pr.id = p.profession_id
             WHERE p.profession_id = 1'
        );
    }
    
    public function getByProfession($professionId)
    {
        return AllFetch('SELECT * FROM personal WHERE profession_id = ?', [$professionId]);
    }
    

    public function create($data)
    {
        return insertRow('personal', $data);
    }
    

    public function update($id, $data)
    {
        unset($data['id']);
        return update('personal', $data, 'id', $id);
    }
    

    public function delete($id)
    {
        return deleteRow('personal', 'id', $id);
    }
    

    public function getAppointments($doctorId, $date = null)
    {
        if ($date) {
            return AllFetch(
                'SELECT * FROM appointments WHERE doctor_id = ? AND date = ?',
                [$doctorId, $date]
            );
        }
        return AllFetch('SELECT * FROM appointments WHERE doctor_id = ?', [$doctorId]);
    }
}