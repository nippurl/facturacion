<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu extends Base
{
    const PATH = __DIR__.'/../../public/img';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @ORM\Column(type="integer")
     */
    private $orden;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }
    public function getImagenFile()
    {
        //Set path for easyadmin
        if ($this->imagen) {

            return realpath(self::PATH .'/../'. $this->imagen);
        } else{
            return null;
        }
    }

    public function setImagenFile(?UploadedFile $filename)
    {
        if ($filename) {
            $name  = $filename->getClientOriginalName().'-'.rand().'.'.$filename->getClientOriginalExtension();
            $name = basename($name);
            $filename->move(self::PATH,$name);
            //Only keep last part of filepath
            $this->setImagen($name);
}


    }
}
