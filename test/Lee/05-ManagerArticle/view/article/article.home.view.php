<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">


    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title?></title>
</head>
<body>
<?php include 'menu.article.view.php' ?>
<?php
var_dump($crazilyNestedSql);
?>
<section class="relative py-16">
    <div class="w-full mb-12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded
  bg-pink-900 text-white">
            <div class="rounded-t mb-0 px-4 py-3 border-0">
                <div class="flex flex-wrap items-center">
                    <div class="relative w-full px-4 max-w-full flex-grow flex-1 ">
                        <h3 class="font-semibold text-lg text-white">Les Articles</h3>
                    </div>
                </div>
            </div>
            <div class="block w-full overflow-x-auto ">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr>
                        <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-pink-800 text-pink-300 border-pink-700">Article</th>
                        <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-pink-800 text-pink-300 border-pink-700">Auteur</th>
                        <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-pink-800 text-pink-300 border-pink-700">Créé</th>
                        <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-pink-800 text-pink-300 border-pink-700">Category</th>
                        <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-pink-800 text-pink-300 border-pink-700">Comments</th>

                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                            <span class="ml-3 font-bold text-white"> Happy Birthday </span></th>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">Lee</td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                            <i class="fas fa-circle text-orange-500 mr-2"></i>27/06/1975</td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                            <div class="flex">
                                <img src="https://demos.creative-tim.com/notus-js/assets/img/team-1-800x800.jpg" alt="..." class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow">
                                <img src="https://demos.creative-tim.com/notus-js/assets/img/team-2-800x800.jpg" alt="..." class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow -ml-4">
                                <img src="https://demos.creative-tim.com/notus-js/assets/img/team-3-800x800.jpg" alt="..." class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow -ml-4">
                                <img src="https://demos.creative-tim.com/notus-js/assets/img/team-4-470x470.png" alt="..." class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow -ml-4">
                            </div>
                        </td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"><div class="flex items-center">
                                <span class="mr-2">6</span>

                            </div>
                        </td>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="relative pt-8 pb-6 mt-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center md:justify-between justify-center">

            </div>
        </div>
    </footer>
</section>

</body>
</html>