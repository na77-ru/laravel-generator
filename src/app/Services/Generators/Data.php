<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Arr;

class Data
{
    /**
     * @var array
     */
    protected $data= [];
    /**
     * Main constructor.
     */
    public function __construct()
    {

    }
    public function __invoke($key)
    {
        return $this->data[$key];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        //dd(__METHOD__, $this->data);
        return $this->data[$key];
    }
    /**
     * @param array $high
     * @param array $low
     * @return array
     */
    protected function merge(array $high, array $low)
    {
        foreach ($high as $key => $value)
        {
            if ($value !== null) {
                $low[$key] = $value;
            }
        }

        return $low;
    }
    /**
     * @param $data
     */
    public function addData($data)
    {
        $this->data = $this->merge($this->data, $data);
            return true;
       // $this->data = Helper::addArr($this->data, $data);
        $arr = $this->data;
        foreach ($data as $key => $item) {
           // dd(__METHOD__, $data, $key, $item);
            if (!is_array($this->data) || !in_array($item, $this->data)) {

                $arr[$key] = $item;

            }
        }

        $this->data = $arr;
    }
    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


}
