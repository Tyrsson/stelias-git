<?php 

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class System_Service_PayPal_Data_PayerName
{

    /**
     * TODO: description.
     * 
     * @var string
     */
    public $salutation;
    
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    public $firstName;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    public $lastName;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    public $middleName;

    /**
     * TODO: description.
     * 
     * @var string
     */
    public $suffix;

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function __construct( array $data = null)
    {
        if(null !== $data) {
            $this->setData($data);
        }
    }
    public function setData(array $data) 
    {
        foreach($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    public function toNvp()
    {
        $data = array();
        $data['SALUTATION'] = $this->salutation;
        $data['FIRSTNAME']  = $this->firstName;
        $data['LASTNAME']   = $this->lastName;
        $data['MIDDLENAME'] = $this->middleName;
        $data['SUFFIX']     = $this->suffix;

        return array_filter( $data );
    }

}
