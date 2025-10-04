<?php

class Usuario{
    private int $id;
    private string $nombre;
    private string $apellido1;
    private string $apellido2;
    private string $email;
    private string $contrasena;
    private array $roles = ['Administrador', 'Profesor'];
    private string $rol;

    public function __construct(string $nombre, string $apellido1, string $apellido2, string $email, string $contrasena, string $rol){
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->compruebaRol($rol);
    }

    //Getters

    public function getId(): int{
        return $this->id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function getApellido1(): string{
        return $this->apellido1;
    }

    public function getApellido2(): string{
        return $this->apellido2;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getPass(): string{
        return $this->contrasena;
    }

    public function getRol(): string{
        return $this->rol;
    }

    //Setters

    private function setId(string $id): void{
        $this->id = $id;
    }

    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }

    public function setApellido1(string $apellido1): void{
        $this->apellido1 = $apellido1;
    }

    public function setApellido2(string $apellido2): void{
        $this->apellido2 = $apellido2;
    }

    public function setEmail(string $email): void{
        $this->email = $email;
    }

    public function setRol(string $rol): void{
        $this->rol = $rol;
    }

    //FUNCIONES DE COMPROBACIÓN
    //Comprueba que el parámetro que se le pasa a la función (rol), se encuentre entre los valores almacenados en el array. Si no lo está devuelve una excepción, si lo está usa el setter para darle el nuevo valor.
    public function compruebaRol(string $rol): void{
        $rol = ucfirst(trim($rol));
        if(in_array($rol, $this->roles)){
            $this->setRol($rol);
        } else {
            throw new CompruebaUsuariosException(null, "El rol no está definido en la base de datos");
        }
    }

    //FUNCIONES DE CASTING
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

    public static function multicast(array $usuarios_recuperados): array{
        $nuevosUsuarios = [];
        foreach ($usuarios_recuperados as $usuario) {
            $nuevoUsuario = self::cast($usuario);
            $nuevosUsuarios[] = $nuevoUsuario;
        }
        return $nuevosUsuarios;
    }
}