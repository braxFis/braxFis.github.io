<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title></title>
<style>

  /* =========================================
SIDEBAR TABS
========================================= */
  .home-sidebar-tabs {
    display: flex;
    border-bottom: 2px solid #222;
    margin-bottom: 15px;
  }

  .home-sidebar-tab {
    flex: 1;
    padding: 10px 15px;
    border: none;
    background: transparent;
    font-size: 16px;
    font-weight: 600;
    color: #777;
    cursor: pointer;
  }

  .home-sidebar-tab.active {
    color: #222;
    background: #f1f1f1;
  }

  .home-tab-content {
    display: none;
  }

  .home-tab-content.active {
    display: block;
  }

  /* =========================================
     HOME PAGE
     ========================================= */

  .home-page {
    padding-top: 40px;
    padding-bottom: 60px;
  }


  /* =========================================
     MAIN CONTENT
     ========================================= */

  .home-main-title {
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: 700;
  }

  .home-article {
    display: flex;
    gap: 24px;
    padding-bottom: 24px;
    margin-bottom: 24px;
    border-bottom: 1px solid #ddd;
  }

  .home-article-image {
    width: 220px;
    height: 125px;
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 6px;
  }

  .home-article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .home-article-content {
    flex: 1;
  }

  .home-article-title {
    margin: 0 0 8px;
    font-size: 22px;
    font-weight: 700;
  }

  .home-article-meta {
    margin-bottom: 8px;
    font-size: 14px;
    color: #777;
  }

  .home-article-genres {
    margin-bottom: 10px;
    font-size: 13px;
    color: #555;
  }

  .home-article-description {
    margin: 0;
    line-height: 1.6;
  }


  /* =========================================
     SIDEBAR
     ========================================= */

  .home-sidebar-section {
    margin-bottom: 40px;
  }

  .home-sidebar-title {
    margin: 0 0 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #222;
    font-size: 22px;
    font-weight: 700;
  }


  /* =========================================
     TOP 10
     ========================================= */

  .top10-item {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 12px 0;

    border-bottom: 1px solid #ddd;
  }

  .top10-number {
    width: 30px;
    flex-shrink: 0;

    font-size: 18px;
    font-weight: 700;
    color: #888;
  }

  .top10-title {
    font-size: 15px;
    font-weight: 600;
  }


  /* =========================================
     UPCOMING
     ========================================= */

  .upcoming-item {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 15px;

    padding: 12px 0;

    border-bottom: 1px solid #ddd;
  }

  .upcoming-title {
    font-size: 15px;
    font-weight: 600;
  }

  .upcoming-date {
    flex-shrink: 0;

    font-size: 13px;
    color: #777;
  }


  /* =========================================
     MOBILE
     ========================================= */

  @media (max-width: 767px) {

    .home-page {
      padding-top: 25px;
    }

    .home-article {
      gap: 15px;
    }

    .home-article-image {
      width: 130px;
      height: 85px;
    }

    .home-article-title {
      font-size: 18px;
    }

    .home-article-description {
      display: none;
    }

  }

  @media (max-width: 600px) {
        .post-item {
            flex-direction: column;
        }

        .post-image {
            width: 100%;
            height: auto;
        }

        .post-content {
            padding: 15px;
        }
    }

    .post-item {
        display: flex;
        background-color: #1c1c1c;
        margin: 20px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        color: #fff;
    }

    .post-image {
        width: 200px;
        height: 150px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .post-content {
        padding: 15px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .post-title {
        margin: 0;
        font-size: 20px;
        color: #ffcc00;
    }

    .post-snippet {
        margin: 10px 0;
        color: #ccc;
        font-size: 14px;
    }

    .post-readmore {
        align-self: flex-start;
        color: #ffcc00;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .post-readmore:hover {
        text-decoration: underline;
    }

    .main-footer {
        background-color: #1c1c1c;
        color: #aaa;
        text-align: center;
        padding: 20px 0;
        font-size: 14px;
        border-top: 1px solid #333;
        margin-top: 60px;
    }

    .main-footer a {
        color: #ffcc00;
        text-decoration: none;
    }

    .main-footer a:hover {
        color: white;
    }

    .main-footer li{
        list-style-type: none;
    }
    .main-menu {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #1c1c1c;
        padding: 15px 30px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    /*.main-menu ul {*/
    /*    list-style: none;*/
    /*    margin: 0;*/
    /*    padding: 0;*/
    /*    display: flex;*/
    /*    gap: 20px;*/
    /*}*/

    /*.main-menu ul li {*/
    /*    display: inline;*/
    /*}*/

    /*.main-menu ul li a {*/
    /*    text-decoration: none;*/
    /*    color: #ffcc00;*/
    /*    font-weight: bold;*/
    /*    font-size: 16px;*/
    /*    padding: 8px 12px;*/
    /*    border-radius: 6px;*/
    /*    transition: background 0.2s ease;*/
    /*}*/

    /*.main-menu ul li a:hover {*/
    /*    background: rgba(255, 204, 0, 0.2);*/
    /*    color: #fff;*/
    /*}*/

    .search-bar {
        display: flex;
        gap: 10px;
    }

    .search-bar input[type="search"] {
        padding: 5px;
    }

    .search-bar button {
        padding: 5px 10px;
        cursor: pointer;
    }
    form {
        max-width: 400px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: #fff;
        font-family: Arial, sans-serif;
    }

    label {
        display: block;
        margin: 1rem 0 0.5rem;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        transition: border 0.2s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #007bff;
        outline: none;
    }

    button {
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }
    .about-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }
    .about-column {
        flex: 1;
        min-width: 280px;
        max-width: 32%;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    .about-column img {
        max-width: 100%;
        height: auto;
    }
    .gallery-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .gallery-thumb {
        max-width: 150px;
        max-height: 100px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #ccc;
    }
</style>
</head>
