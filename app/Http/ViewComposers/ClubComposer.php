<?php

namespace App\Http\ViewComposers;

use App\Repositories\ClubRepository;
use Illuminate\View\View;

class ClubComposer
{
    /**
     * The club repository implementation.
     *
     * @var ClubRepository
     */
    protected $clubs;

    /**
     * Create a new club composer.
     *
     * @param ClubRepository $clubs
     *
     * @return void
     */
    public function __construct(ClubRepository $clubs)
    {
        // Dependencies automatically resolved by service container...
        $this->clubs = $clubs;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('clubs', $this->clubs->getClubs());
    }
}
