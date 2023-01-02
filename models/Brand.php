<?php

require_once '../helpers/database.php';

class Brand
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        try {
            $conn = getDbConnection();
            $result = $conn->query('SELECT id AS id, brand_name AS name FROM brand')->fetchAll();
        } catch(Exception) {
            $conn = null;
            return [];
        }

        $conn = null;
        return $result ?: [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function add(string $name): bool
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('INSERT INTO brand VALUES (null, :name)');
            $stmt->execute(['name' => $name]);
        } catch(Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('DELETE FROM brand WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } catch (Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return true;
    }

    /** ======== Helpers ======== */

    /**
     * @param int $id
     * @return string|null
     */
    public function findById(int $id): ?string
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('SELECT brand_name AS name FROM brand WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception) {
            $conn = null;
            return null;
        }

        $conn = null;
        return $result['name'] ?: null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function nameExists(string $name): bool
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('SELECT * FROM brand WHERE brand_name = :name');
            $stmt->execute(['name' => $name]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return !empty($result);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isUsed(int $id): bool
    {
        try {
            $conn = getDbConnection();
            $stmt = $conn->prepare('SELECT * FROM product WHERE brand_id = :id');
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception) {
            $conn = null;
            return false;
        }

        $conn = null;
        return !empty($result);
    }
}