<?php

namespace App\Controller\Admin;

use App\Entity\ContactRequest;
use App\Entity\Education;
use App\Entity\Interest;
use App\Entity\Language;
use App\Entity\Location;
use App\Entity\PortfolioReference;
use App\Entity\Profile;
use App\Entity\Project;
use App\Entity\ProjectPhoto;
use App\Entity\Skill;
use App\Entity\Technology;
use App\Entity\User;
use App\Entity\Work;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class MyDashboardController extends AbstractDashboardController
{   
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        
        return $this->render('admin/dashboard/index.html.twig', [
            
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio API')
            // you can include HTML contents too (e.g. to link to an image)
            // ->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

            // by default EasyAdmin displays a black square as its default favicon;
            // use this method to display a custom favicon: the given path is passed
            // "as is" to the Twig asset() function:
            // <link rel="shortcut icon" href="{{ asset('...') }}">
            ->setFaviconPath('favicon.svg')

            // the domain used by default is 'messages'
            ->setTranslationDomain('my-custom-domain')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            ->renderSidebarMinimized()

            // by default, users can select between a "light" and "dark" mode for the
            // backend interface. Call this method if you prefer to disable the "dark"
            // mode for any reason (e.g. if your interface customizations are not ready for it)
            ->disableDarkMode()

            // by default, the UI color scheme is 'auto', which means that the backend
            // will use the same mode (light/dark) as the operating system and will
            // change in sync when the OS mode changes.
            // Use this option to set which mode ('light', 'dark' or 'auto') will users see
            // by default in the backend (users can change it via the color scheme selector)
            ->setDefaultColorScheme('dark')
            // instead of magic strings, you can use constants as the value of
            // this option: EasyCorp\Bundle\EasyAdminBundle\Config\Option\ColorScheme::DARK

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls()

            // set this option if you want to enable locale switching in dashboard.
            // IMPORTANT: this feature won't work unless you add the {_locale}
            // parameter in the admin dashboard URL (e.g. '/admin/{_locale}').
            // the name of each locale will be rendered in that locale
            // (in the following example you'll see: "English", "Polski")
            ->setLocales(['en', 'fr'])
            // to customize the labels of locales, pass a key => value array
            // (e.g. to display flags; although it's not a recommended practice,
            // because many languages/locales are not associated to a single country)
            ->setLocales([
                'en' => '🇬🇧 English',
                'fr' => '🇫🇷 Français',
            ])
            // to further customize the locale option, pass an instance of
            // EasyCorp\Bundle\EasyAdminBundle\Config\Locale
            // ->setLocales([
            //     'fr', // locale without custom options
            //     ConfigLocale::new('fr', '🇫🇷 Français','far fa-language') // custom label and icon
            // ])
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::subMenu('Portfolio', 'fa fa-image')->setSubItems([
            MenuItem::linkToCrud('Contact', 'fa fa-envelope', ContactRequest::class),   
            MenuItem::linkToCrud('Technologies', 'fa fa-laptop-code', Technology::class),
            MenuItem::linkToCrud('Projets', 'fa fa-project-diagram', Project::class),
            MenuItem::linkToCrud('Projets Photos', 'fa fa-images', ProjectPhoto::class),                      
        ]);

        yield MenuItem::subMenu('Utilisateurs', 'fa fa-users')->setSubItems([
            MenuItem::linkToCrud('Gestion des utilisateurs', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Localisation', 'fa fa-map-marker-alt', Location::class),
            MenuItem::linkToCrud('Profils', 'fa fa-user-cog', Profile::class),
        ]);

        yield MenuItem::subMenu('Informations', 'fa fa-info-circle')->setSubItems([                       
            MenuItem::linkToCrud('Expériences', 'fa fa-briefcase', Work::class),
            MenuItem::linkToCrud('Formations', 'fa fa-graduation-cap', Education::class),
            MenuItem::linkToCrud('Skills', 'fa fa-laptop-code', Skill::class),            
            MenuItem::linkToCrud('Intérets', 'fa fa-heart', Interest::class),
            MenuItem::linkToCrud('Langues', 'fa fa-language', Language::class),
            MenuItem::linkToCrud('Références', 'fa fa-star', PortfolioReference::class),
        ]);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getName() ?? 'User')
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)
            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            // ->setGravatarEmail($user->getUserName())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                // MenuItem::linkToRoute('app_login', 'fa fa-id-card', '...', ['...' => '...']), //test
                MenuItem::linkToCrud('Profile', 'fa fa-user-cog', User::class)
                    ->setAction('detail')
                    ->setEntityId($this->getUser()->getId()),



            ]);
    }
}
