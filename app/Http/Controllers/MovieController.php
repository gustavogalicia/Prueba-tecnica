<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginLog;

class MovieController extends Controller
{
    //

    public function listMovie(){
        try {

            $token = config('app.tmdb_token');
            $client = new \GuzzleHttp\Client();
        
            $page = request()->page;

            $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/changes?page='.$page, [
                'headers' => [
                    'Authorization' => 'Bearer '. $token,
                    'accept' => 'application/json',
                ],
            ]);

            $user = auth()->user();
            $log = LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => request()->path(),
            ]);

            return response()->json([
                'status' => 'success',
                'movies' => json_decode($response->getBody()),
            ]);
         
        } catch (ConnectException $e) {
            $response['status'] = 404;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (RequestException $e) {
            $response['status'] = $e->getResponse()->getStatusCode();
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function SearchMovie(){
       
        try {

            $token = config('app.tmdb_token');
            $client = new \GuzzleHttp\Client();
        
            $request = request()->all();

            $response = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?query='.$request['query'].'&include_adult=false&language=en-US&page=1', [
                'headers' => [
                'Authorization' => 'Bearer '. $token,
                'accept' => 'application/json',
                ],
            ]);
            
            $user = auth()->user();
            LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => request()->path(),
            ]);

            return response()->json([
                'status' => 'success',
                'movies' => json_decode($response->getBody()),
            ]);
           
        } catch (ConnectException $e) {
            $response['status'] = 404;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (RequestException $e) {
            $response['status'] = $e->getResponse()->getStatusCode();
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function AddFavoriteMovie(){
        
       
        try {

            $token = config('app.tmdb_token');
            $client = new \GuzzleHttp\Client();
        
            $account_id = request('account_id');
            $body = json_encode(request()->all());

            $response = $client->request('POST', 'https://api.themoviedb.org/3/account/'.$account_id.'/favorite', [
                'body' => $body,
                'headers' => [
                  'Authorization' => 'Bearer '. $token,
                  'accept' => 'application/json',
                  'content-type' => 'application/json',
                ],
              ]);
              
              $user = auth()->user();
                LoginLog::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'url' => request()->path(),
                ]);

            return response()->json([
                'status' => 'success',
                'movies' => json_decode($response->getBody()),
            ]);
            $response['status'] = $result->getStatusCode();
            $response['message'] = json_decode($result->getBody()->getContents());
        } catch (ConnectException $e) {
            $response['status'] = 404;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (RequestException $e) {
            $response['status'] = $e->getResponse()->getStatusCode();
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function ListFavoriteMovie(){
        
       
        try {

            $token = config('app.tmdb_token');
            $client = new \GuzzleHttp\Client();
        
            $account_id = request('account_id');
            $language = request('language');
            $page = request('page');

            $response = $client->request('GET', 'https://api.themoviedb.org/3/account/'.$account_id.'/favorite/movies?language='.$language.'&page='.$page.'&sort_by=created_at.asc', [
                'headers' => [
                'Authorization' => 'Bearer '. $token,
                'accept' => 'application/json',
                ],
            ]);
            $user = auth()->user();
            LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => request()->path(),
            ]);


            return response()->json([
                'status' => 'success',
                'movies' => json_decode($response->getBody()),
            ]);
            $response['status'] = $result->getStatusCode();
            $response['message'] = json_decode($result->getBody()->getContents());
        } catch (ConnectException $e) {
            $response['status'] = 404;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (RequestException $e) {
            $response['status'] = $e->getResponse()->getStatusCode();
            $response['message'] = $e->getMessage();
            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
