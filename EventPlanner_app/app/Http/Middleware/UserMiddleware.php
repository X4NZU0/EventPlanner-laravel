UserMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user') && !session()->has('admin')) {
            return redirect('/login')->withErrors(['access' => 'Login required.']);
        }

        return $next($request);
    }
}
