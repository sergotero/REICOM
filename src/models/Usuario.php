<?php
/**
 * Clase Usuario
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Usuario{
    
    /**
     * Identificador único del usuario.
     *
     * @var integer
     */
    private int $id;

    /**
     * Nombre del usuario.
     *
     * @var string
     */
    private string $nombre;

    /**
     * Primer apellido del usuario.
     *
     * @var string
     */
    private string $apellido1;

    /**
     * Segundo apellido del usuario.
     *
     * @var string
     */
    private string $apellido2;

    /**
     * Email del usuario.
     *
     * @var string
     */
    private string $email;

    /**
     * Contraseña del usuario.
     *
     * @var string
     */
    private string $contrasena;

    /**
     * Array que contiene los roles definidos en la base de datos.
     *
     * @var array
     */
    private array $roles = ['Administrador', 'Profesor'];

    /**
     * Rol asignado al usuario.
     *
     * @var string
     */
    private string $rol;

    /**
     * Constructor de la clase Usuario.
     *
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2
     * @param string $email
     * @param string $contrasena
     * @param string $rol
     */
    public function __construct(string $nombre, string $apellido1, string $apellido2, string $email, string $contrasena, string $rol){
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->compruebaRol($rol);
    }

    /**
     * Devuelve el identificador del usuario.
     *
     * @return integer
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * Devuelve el nombre del usuario.
     *
     * @return string
     */
    public function getNombre(): string{
        return $this->nombre;
    }

    /**
     * Devuelve el primer apellido del usuario.
     *
     * @return string
     */
    public function getApellido1(): string{
        return $this->apellido1;
    }

    /**
     * Devuelve el segundo apellido del usuario.
     *
     * @return string
     */
    public function getApellido2(): string{
        return $this->apellido2;
    }

    /**
     * Devuelve el email del usuario.
     *
     * @return string
     */
    public function getEmail(): string{
        return $this->email;
    }

    /**
     * Devuelve la contraseña del usuario
     * 
     * La contraseña se almacena en la base de datos hasheada.
     *
     * @return string
     */
    public function getPass(): string{
        return $this->contrasena;
    }

    /**
     * Devuelve el rol del usuario.
     *
     * @return string
     */
    public function getRol(): string{
        return $this->rol;
    }

    /**
     * Establece el identificador del usuario.
     *
     * @param string $id
     * @return void
     */
    private function setId(string $id): void{
        $this->id = $id;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $nombre
     * @return void
     */
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }

    /**
     * Establece el primer apellido del usuario.
     *
     * @param string $apellido1
     * @return void
     */
    public function setApellido1(string $apellido1): void{
        $this->apellido1 = $apellido1;
    }

    /**
     * Establece el segundo apellido del usuario.
     *
     * @param string $apellido2
     * @return void
     */
    public function setApellido2(string $apellido2): void{
        $this->apellido2 = $apellido2;
    }

    /**
     * Establece el email del usuario.
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void{
        $this->email = $email;
    }

    /**
     * Establece el rol del usuario.
     *
     * @param string $rol
     * @return void
     */
    public function setRol(string $rol): void{
        $this->rol = $rol;
    }

    /**
     * Comprueba si el rol asignado, se encuentra definido en el array $roles. 
     *
     * Se trata de un método interno que se ejecuta en el constructor de la clase.
     * 
     * @param string $rol
     * @throws CompruebaUsuarioException si el rol no se encuentra definido en el array $roles
     * @return void
     */
    private function compruebaRol(string $rol): void{
        $rol = ucfirst(trim($rol));
        if(in_array($rol, $this->roles)){
            $this->setRol($rol);
        } else {
            throw new CompruebaUsuarioException(null, "El rol no está definido en la base de datos");
        }
    }

    /**
     * Genera un nuevo Usuario a partir de un objeto recuperado de la base de datos.
     *
     * @param stdClass $usuario_recuperado
     * @return Usuario
     */
    public static function cast(stdClass $usuario_recuperado): Usuario{
        $usuario = new Usuario(
            $usuario_recuperado->nombre,
            $usuario_recuperado->apellido1,
            $usuario_recuperado->apellido2,
            $usuario_recuperado->email,
            $usuario_recuperado->pass,
            $usuario_recuperado->rol
        );
        $usuario->setId($usuario_recuperado->id);
        return $usuario;
    }

    /**
     * Genera un array de Usuarios a partir de un array de objetos recuperados de la base de datos.
     *
     * @param array $usuarios_recuperados
     * @return Usuario[]
     */
    public static function multicast(array $usuarios_recuperados): array{
        $nuevosUsuarios = [];
        foreach ($usuarios_recuperados as $usuario) {
            $nuevoUsuario = self::cast($usuario);
            $nuevosUsuarios[] = $nuevoUsuario;
        }
        return $nuevosUsuarios;
    }
}