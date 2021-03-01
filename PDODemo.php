<?php


class PDODemo
{
    protected $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
            DB_USER, DB_PWD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public function runSimpleQuery(): array
    {
        $res = array();
        $stmt = $this->db->query('SELECT DISTINCT make, model FROM car ORDER BY make, model');
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row['make'] . ': ' . $row['model'];
        }
        return $res;
    }

    public function runFetchAllQuery(): array
    {
        $stmt = $this->db->query('SELECT DISTINCT make, model FROM car ORDER BY make, model');
        return $stmt->fetchAll();
    }

    /**
     * Demonstrates how to insert data in a database. After running this function, the given make will truly exist in
     * the car_brand table and the given model will truly exist in the car_model table. The function will not fail if
     * the model or the make is already present in the database
     * @param string $make the name of the car brand
     * @param string $model the name of the model
     */
    public function runInsert(string $make, string $model)
    {
        $this->db->beginTransaction();

        // Check to see if the car model is already there:
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM car_model WHERE make = :make AND model = :model');
        $stmt->bindValue(':make', $make);
        $stmt->bindValue(':model', $model);
        $stmt->execute();
        // Car model is already in the database
        if ($stmt->fetch(PDO::FETCH_NUM)[0] == 1) {
            return;
        }

        // Check to see if the car brand is already there:
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM car_brand WHERE make = :make');
        $stmt->bindValue(':make', $make);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_NUM)[0] == 0) {
            // Need to add the car brand first

            $stmt = $this->db->prepare('INSERT INTO car_brand (make) VALUES(:make)');
            $stmt->bindValue(':make', $make);
            $stmt->execute();
        }

        // Inserting the car model
        $stmt = $this->db->prepare('INSERT INTO car_model (make, model) VALUES(:make, :model)');
        $stmt->bindValue(':make', $make);
        $stmt->bindValue(':model', $model);
        $stmt->execute();
        $this->db->commit();
    }

    public function runAutoIncrementInsert(string $make, string $model, string $model_year, int $mileage, string $fuel,
                                           string $type, int $price, int $dealer_id, string $comment = null): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO car (make, model, model_year, mileage, fuel, type, price, dealer_id, comment)'
            . ' VALUES(:make, :model, :model_year, :mileage, :fuel, :type, :price, :dealer_id, :comment)');
        $stmt->bindValue(':make', $make);
        $stmt->bindValue(':model', $model);
        $stmt->bindValue(':model_year', $model_year);
        $stmt->bindValue(':mileage', $mileage);
        $stmt->bindValue(':fuel', $fuel);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':dealer_id', $dealer_id);
        $stmt->bindValue(':comment', $comment);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function runUpdate(int $carId, int $newPrice)
    {
        $stmt = $this->db->prepare('UPDATE car SET price = :price WHERE id = :id');
        $stmt->bindValue(':id', $carId);
        $stmt->bindValue(':price', $newPrice);
        $stmt->execute();
    }

    public function runDelete(int $carId)
    {
        $stmt = $this->db->prepare('DELETE FROM car WHERE id = :id');
        $stmt->bindValue(':id', $carId);
        $stmt->execute();
   }

    /**
     * A function demonstrating a more complex query. The query will call runInsert() for all car models in the given
     * counties - but not cars having the text "demo" anywhere in the comment field.
     * @param array $countyNames an array of county names from where to find car models.
     * @uses PDODemo::runInsert()
     */
   public function runComplexUpdate(array $countyNames)
   {
       $query = 'SELECT DISTINCT make, model FROM car INNER JOIN dealer on dealer_id = dealer.id'
              . ' INNER JOIN county ON no = county_no WHERE name IN ';

       $inClause = '(:p0';
       for ($i = 1; $i < count($countyNames); $i++){
           $inClause .= ',:p' . $i;
       }
       $inClause .= ')';
       $query .= $inClause;
       $query .= " AND (comment NOT LIKE '%demo%' OR comment IS NULL)";

       $stmt = $this->db->prepare($query);
       for ($i = 0; $i < count($countyNames); $i++){
           $stmt->bindValue(':p' . $i, $countyNames[$i]);
       }
       $stmt->execute();

       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           $this->runInsert($row['make'], $row['model']);
       }
   }

    /**
     * A function demonstrating another complex query. The query will call find the name of car brands, the dealer_id
     * and the number of cars of a given brand ($largerBrand) - except for dealers having the same number or more cars
     * of another brand of cars ($smallerBrand).
     * @param string $largerBrand the name of the brand to be counted.
     * @param string $largerBrand the name of the brand to compared agains.
     * @return array an array of the form array(array('dealer_id' => '...', 'count' => n))
     */
   public function runComplexQuery(string $largerBrand, string $smallerBrand): array
   {
       $res = array();

       $query = 'SELECT dealer_id, COUNT(*) AS count FROM car AS v WHERE make = :largerBrand GROUP BY dealer_id'
              . ' HAVING count > (SELECT COUNT(*) FROM car AS a WHERE make = :smallerBrand AND v.dealer_id = a.dealer_id)';

       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':largerBrand', $largerBrand);
       $stmt->bindValue(':smallerBrand', $smallerBrand);
       $stmt->execute();

       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           $res[] = $row;
       }
       return $res;
   }
}