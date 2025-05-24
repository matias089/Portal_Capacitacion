<?php
namespace PortalCapacitacion;

class LoginController
{
    private $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function handleLogin($rut, $password)
    {
        // Validate input
        if (empty($rut) || empty($password)) {
            header("Location: /Portal_Capacitacion/login.php?error=1");
            exit();
        }
        
        // Attempt authentication
        $userData = $this->authService->authenticate($rut, $password);
        
        if ($userData) {
            // Authentication successful
            $redirectUrl = $this->authService->getRedirectUrl($userData['tipo_usuario']);
            header("Location: " . $redirectUrl);
            exit();
        } else {
            // Authentication failed
            header("Location: /Portal_Capacitacion/login.php?error=1");
            exit();
        }
    }
}
