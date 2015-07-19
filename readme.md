## domin sample ##

This project serves as an example on how to use [domin] to generate a administrative user interface. A demo is deployed on http://domin.rtens.org. The project mimics a simple blog and there is also a *demo action* showcasing all available input elements.

[domin]: https://github.com/rtens/domin


## Installation ##

Download the project with [git] and build it with [composer]

    git clone https://github.com/rtens/domin-sample.git
    cd domin-sample
    composer install
    
Start a development server to run the web application on [localhost:8080](http://localhost:8080)

    php -S localhost:8080 index.php
    
To run the CLI application, enter
    
    php cli.php

[composer]: http://getcomposer.org
[git]: http://git-scm.org
