<?php
// membersihkan input user, dan anti script injection
if (!function_exists('senitize_input')) {
    function senitize_input($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}

// pengamanan saat menampilkan view 
if (!function_exists('escape_output')) {
    function escape_output($data)
    {
        return esc($data);
    }
}

if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token()
    {
        return csrf_hash();
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token)
    {
        return hash_equals(csrf_hash(), $token);
    }
}

if (!function_exists('hash_password')) {
    function hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password')) {
    function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
}

if (!function_exists('secure_filename')) {
    function secure_filename($filename)
    {
        $filename = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $filename);
        return strtolower($filename);
    }
}

if (!function_exists('validate_ip')) {
    function validate_ip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }
}

if (!function_exists('secure_filename')) {
    function secure_filename($filename)
    {
        $filename = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $filename);
        return strtolower($filename);
    }
}

if (!function_exists('validate_ip')) {
    function validate_ip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }
}

if (!function_exists('is_ajax_request')) {
    function is_ajax_request()
    {
        return service('request')->isAJAX();
    }
}

if (!function_exists('get_user_ip')) {
    function get_user_ip()
    {
        return service('request')->getIPAddress();
    }
}

if (!function_exists('limit_login_attempt')) {
    function limit_login_attempt($key, $max_attempt = 5, $timeout = 300)
    {
        $session = session();

        $attempts = $session->get($key) ?? 0;
        $last_attempt = $session->get($key . '_time') ?? time();

        if ($attempts >= $max_attempt && (time() - $last_attempt) < $timeout) {
            return false;
        }

        $session->set($key, $attempts + 1);
        $session->set($key . '_time', time());

        return true;
    }
}

if (!function_exists('reset_login_attempt')) {
    function reset_login_attempt($key)
    {
        session()->remove($key);
        session()->remove($key . '_time');
    }
}

if (!function_exists('xss_clean_custom')) {
    function xss_clean_custom($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

function secure_headers()
{
    header("X-Frame-Options: SAMEORIGIN");
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer-when-downgrade");

    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://code.jquery.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com; connect-src 'self' https://cdnjs.cloudflare.com;");
}

if (!function_exists('encrypt_data')) {
    function encrypt_data($data)
    {
        $encypter = \Config\Services::encrypter();

        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        $encypted = $encypter->encrypt($data);

        // encode supaya aman disimpan db/url
        return base64_encode($encypted);
    }
}

if (!function_exists('decrypt_data')) {
    function decrypt_data($data)
    {
        $encrypter = \Config\Services::encrypter();

        // decode dari base64
        $decoded = base64_decode($data);

        try {
            $decrypted = $encrypter->decrypt($decoded);

            // coba decode JSON (jika sebelumnya array/object)
            $json = json_decode($decrypted, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $json : $decrypted;
        } catch (\Exception $e) {
            return false; // gagal decrypt
        }
    }
}
