<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 02.02.16
 * Time: 12:16
 */


class Session
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;

    // The state of the session
    private $sessionState = self::SESSION_NOT_STARTED;

    // THE only instance of the class
    static private  $instance;


    private function __construct() {}


    /**
     *    Стандарный синглтон. Хотя лучше его не использовать
     *    Метод возвращает свойство класса, в котором ссылка на объект класса
     *    Если Объекта нет, то он создается и помещается в свойство класса
     *
     *    @return    object
     **/

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }


    /**
     *    (Re)starts the session.
     *
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/

    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }


    /**
     *    Stores data in the session.
     *    Example: $instance->foo = 'bar';
     *
     *    @param    name    Name of the data.
     *    @param    value    Your data.
     *    @return    void
     **/

    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;

    }


    /**
     *    Gets datas from the session.
     *    Example: echo $instance->foo;
     *
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/

    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }


    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }


    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }


    /**
     *    Destroys the current session.
     *
     *    @return    bool    TRUE is session has been deleted, else FALSE.
     **/

    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );

            return !$this->sessionState;
        }

        return FALSE;
    }
}