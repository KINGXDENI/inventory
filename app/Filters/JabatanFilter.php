<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JabatanFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->to('/login'); // Perhatikan, tidak ada '/' sebelum 'login'
        }
        // Ambil jabatan user
        $role = session()->get('pengguna')['jabatan']; // Misalnya 'admin' atau 'manager'

        // Halaman yang hanya bisa diakses oleh manager
        $managerOnlyPages = [
            'laporan', 'login', 'logout'
        ];


        // Cek URL saat ini
        $uri = service('uri');
        $segment = $uri->getSegment(1); // Ambil segmen pertama dari URL

        // Cek akses berdasarkan role
        if ($role == 'manager' && !in_array($segment, $managerOnlyPages)) {
            return redirect()->to('/laporan'); // Redirect ke halaman yang diizinkan
        }

        if ($role == 'admin') {
            // Admin memiliki akses penuh
            return;
        }

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
