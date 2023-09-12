<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>MoneyEP | Budget Management</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
</head>
<body style="width: 100%; height: 100vh; background-color: #F5F5F9;">

<div class="sidebar text-center">
    <a class="navbar-brand logo" href="#">
        <img src="https://logos-world.net/wp-content/uploads/2021/07/Money-Logo.png" alt="MoneyEP" width="100" height="80">
    </a>
    <a href="dashboard"><box-icon type='solid' name='dashboard' style="vertical-align: middle; margin-right: 11px" color="#566A7F" pull="left"></box-icon>Dashboard</a>
    <a href="myassets"><box-icon name='money-withdraw' style="vertical-align: middle; margin-right: 11px" color="#566A7F" pull="left"></box-icon>My Assets</a>
    <a href="mydebts"><box-icon name='stopwatch' style="vertical-align: middle; margin-right: 11px" color="#566A7F" pull="left"></box-icon>My Debts</a>
    <a href="account-settings"><box-icon name='cog' style="vertical-align: middle; margin-right: 11px" color="#566A7F" pull="left"></box-icon>Settings</a>
    <a href="logout"><box-icon name='log-out-circle' style="vertical-align: middle; margin-right: 11px" color="#566A7F" pull="left"></box-icon>Log out</a>
</div>
<div class="content">
    <?= $this->renderSection('dash_content') ?>
</div>





























<style>

    .sidebar {
        margin: 0;
        padding: 0;
        width: 200px;
        background-color: #FFFFFF;
        position: fixed;
        height: 100%;
        overflow: auto;
    }

    .sidebar a {
        display: block;
        color: #5C7084;
        padding: 16px;
        text-decoration: none;
    }

    .sidebar a.active {
        background-color: #04AA6D;
        color: white;
    }

    .sidebar a:hover:not(.logo) {
        background-color: #F8F8F9;
        /*color: white;*/
    }

    div.content {
        margin-left: 200px;
        padding: 1px 16px;
        height: 1000px;
    }

    @media screen and (max-width: 700px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        .sidebar a {float: left;}
        div.content {margin-left: 0;}
    }

    @media screen and (max-width: 540px) {
        .sidebar a {
            text-align: center;
            float: none;
        }
        .sidebar{
            display: flex;
            flex-direction: column;
        }
    }

    .tarihth{
        cursor: pointer;
    }




</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js" integrity="sha512-fHY2UiQlipUq0dEabSM4s+phmn+bcxSYzXP4vAXItBvBHU7zAM/mkhCZjtBEIJexhOMzZbgFlPLuErlJF2b+0g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>

</body>
</html>