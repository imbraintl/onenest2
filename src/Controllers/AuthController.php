<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Database;

class AuthController
{
    public function showLogin(): void
    {
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function showRegister(): void
    {
        include __DIR__ . '/../../views/auth/register.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Email and password are required';
            include __DIR__ . '/../../views/auth/login.php';
            return;
        }

        $user = User::findByEmail($email);
        
        if (!$user || !User::verifyPassword($password, $user['password'])) {
            header('Location: /?page=page-login&error=' . urlencode('Invalid email or password'));
            return;
        }

        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role_flags'] = json_decode($user['role_flags'], true) ?? [];

        // Update last login
        User::updateLastLogin($user['id']);

        // Redirect to appropriate dashboard
        $this->redirectToDashboard($_SESSION['role_flags']);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegister();
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        
        if (empty($name)) $errors[] = 'Name is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (empty($password)) $errors[] = 'Password is required';
        if ($password !== $confirmPassword) $errors[] = 'Passwords do not match';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';

        // Check if email already exists
        if (User::findByEmail($email)) {
            $errors[] = 'Email already exists';
        }

        if (!empty($errors)) {
            header('Location: /?page=page-join&errors=' . urlencode(implode(',', $errors)));
            return;
        }

        // Create user
        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role_flags' => ['user'] // Default role
        ]);

        // Create profile
        Database::insert('profiles', [
            'user_id' => $userId
        ]);

        // Auto login
        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['role_flags'] = ['user'];

        // Redirect to user dashboard
        header('Location: /dashboard/user');
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

    private function redirectToDashboard(array $roles): void
    {
        // Determine which dashboard to redirect to based on roles
        if (in_array('admin', $roles)) {
            header('Location: /dashboard/admin');
        } elseif (in_array('business', $roles)) {
            header('Location: /dashboard/business');
        } elseif (in_array('recruiter', $roles)) {
            header('Location: /dashboard/recruiter');
        } elseif (in_array('job_seeker', $roles)) {
            header('Location: /dashboard/job-seeker');
        } elseif (in_array('property_agent', $roles)) {
            header('Location: /dashboard/property-agent');
        } elseif (in_array('property_owner', $roles)) {
            header('Location: /dashboard/property-owner');
        } else {
            header('Location: /dashboard/user');
        }
        exit;
    }
}