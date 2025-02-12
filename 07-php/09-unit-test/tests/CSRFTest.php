<?php 
declare(strict_types=1);
require __DIR__. "/../../ressources/services/_csrf.php";
use PHPUnit\Framework\TestCase;

final class CSRFTest extends TestCase
{
    public function testCanSetCSRF()
    {
        setCSRF();
        $this->assertArrayHasKey("token", $_SESSION, "Le jeton CSRF a bien été créé");
    }
    public function testCanConfirmCSRF()
    {
        $_POST["token"] = $_SESSION["token"];
        $this->assertTrue(isCSRFValid(), "Le jeton CSRF est valide");
    }
    public function testCannotConfirmCSRF()
    {
        $_POST["token"] = "chausette";
        $this->assertFalse(isCSRFValid(), "Le jeton CSRF est invalide");
    }
}