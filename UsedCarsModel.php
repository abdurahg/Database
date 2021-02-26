<?php
/*
 * The following constants need to be defined for this class to work properly:
 * DB_HOST: The host running MariaDB - typically, 127.0.0.1.
 * DB_NAME: The name of the MariaDB used car database.
 * DB_USER: The name of a MariaDB user having SELECT privilege to the DB_NAME tables.
 * DB_PWD: The password of DB_USER.
 *
 */

/**
 * Class UsedCarsModel
 * A class for generating JSON documents for the contents of the used car example database used in the
 * IDATG2204 database course.
 * @author Rune Hjelsvold <rune.hjelsvold@ntnu.no>
 */
class UsedCarsModel
{
    /**
     * @var PDO
     */
    protected $db;

    /**
     * @throws PDOException if the connection to the database could not be established.
     */
    public function __construct()
    {
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
            DB_USER, DB_PWD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    /**
     * Retrieves information about dealers and their used cars from the used car database.
     * @return array an array of associative arrays holding information about dealers and an array of dealer cars:
     *         array(array('id' => '...', 'city' => '...', 'county' => '...', 'cars' => array(
     *         array('id' => '...', 'make' => '...', 'model' => '...', 'model_year' => '...', ...), ...)), ...)
     */
    public function getDealersWithCars(): array
    {
        $res = array();

        $query = 'SELECT id, city, name FROM dealer INNER JOIN county ON county_no = county.no';

        $stmt = $this->db->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pos = count($res);
            $res[] = array();

            $res[$pos]['id'] = $row['id'];
            $res[$pos]['city'] = $row['city'];
            $res[$pos]['county'] = $row['name'];

            $res[$pos]['cars'] = $this->getCarsForDealer($row['id']);
        }

        return $res;
    }

    /**
     * Retrieves information about a used car for sales at the dealer from the database.
     * @param $dealerId id of the dealer
     * @return array an array of associative arrays of the form:
     *         array('id' => '...', 'make' => '...', 'model' => '...', 'model_year' => '...', ...), ...)), ...)
     */
    public function getCarsForDealer($dealerId) {
        $res = array();

        $query = "SELECT id, make, model, model_year, mileage, fuel, type, price, comment FROM car WHERE dealer_id = :dealer_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':dealer_id', $dealerId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pos = count($res);
            $res[] = $row;
        }

        return $res;
    }

    /**
     * Retrieves information about counties and cities from the used car database.
     * @return array an array of associative arrays holding county names and array of names of cities in each county:
     *               array(array('name' => '...', 'cities' => array(array('name' => '...'), ...)), ...)
     */
    public function getCounties(): array
    {
        $res = array();
        $query = 'SELECT no, name FROM county';

        $this->db->beginTransaction();

        $stmt = $this->db->query($query);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pos = count($res);
            $res[] = array();
            $res[$pos]['name'] = $row['name'];
            $res[$pos]['cities'] = $this->getCitiesForCounty($row['no']);
        }
        $this->db->commit();

        return $res;
    }

    /**
     * Retrieves information about cities for a given county from the used car database.
     * @param $countyNo int the county number
     * @return array an array of names of cities in each county:
     *               array(array('name' => '...'), ...)
     */
    public function getCitiesForCounty(int $countyNo): array
    {
        $res = array();
        $query = 'SELECT city FROM dealer WHERE county_no = :county_no';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':county_no', $countyNo);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $pos = count($res);
            $res[] = array();
            $res[$pos]['name'] = $row[0];
        }

        return $res;
    }

    /**
        }
     * Generates a JSON document containing a list of dealers, each dealer holding an array of their cars.
     * @return string a JSON string of the format:
     *                [{ "id": "...", "city": "...", "county": "...", "cars": [
     *                 {"id": "...", "make": "...", "model": "...", "model_year": "...", ... }, ...]}, ...]
     */
    public function createDealersDoc(): string
    {
        return json_encode($this->getDealersWithCars());
    }

    /**
     * Generates a JSON document containing names of counties and a nested list of city names for each county.
     * @return string a JSON string of the format:
     *                [{ "name": "...", "cities": [{"name": "..."}, ...]}, ...]
     */
    public function createCountiesCitiesDoc(): string
    {
        return json_encode($this->getCounties());
    }
}