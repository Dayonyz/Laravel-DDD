<?php

use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Application\Bus\Command\CreateBusinessAccountBaseCommand;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'identity-access', 'as' => 'identity-access.'], function () {
    Route::get('/', function () {
        CreateBusinessAccountBaseCommand::dispatch(
            businessName: 'My new business',
            businessLogo: 'https://img.freepik.com/9cd18345f33e5155330d7692&w=2000',
            businessEmail: 'new.business@gmail.com',
            businessPhoneCode: '380',
            businessPhone: '684930506',
            businessPostCode: '64100',
            businessCountryIso: 'UA',
            businessCity: 'Kharkov',
            businessAddressLine1: 'st. Naukova lane, 68, ap.129',
            businessWebsite: 'https://pixabay.com/',
            businessIsActive: false,
            userTitle: UserTitleEnum::MR->value,
            userFirstName: 'Denys',
            userLastName: 'Pisklov',
            userPassword: 'Qq12345678'
        );

        return view('identity-access.welcome');
    });
});
