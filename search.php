<!DOCTYPE html>
<?php
 require "functions.php";
 // https://stackoverflow.com/a/1764199
 if ($_GET) {
     // https://www.php.net/manual/en/reserved.variables.server.php
     parse_str($_SERVER['QUERY_STRING'], $url_params);

     $resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
     $data = json_decode($resp, true);
     $entries = getSearchResults($data, $url_params);
     $years = arrangeEntriesByYear($entries);
 }

 ?>
<html>
  <head>
    <title>Journal Search</title>
    <?php require "common_head.php"; ?>
    <style type="text/css">
      .topnav {
          margin-left: 10px;
          margin-top: 30px;
          width: 100%;
      }
    </style>
  </head>
  <body>
    <div class="topnav">
      <form action="./search.php" method="get">
        Keywords: <input name="keywords" type="text" ><br>
        <hr style="width:600px; margin-left:0px">
        <!--Accurate Search： <input name="precise_string" type="text" ><br>
        <hr style="width:600px; margin-left:0px">-->
        Time range from 
        <select name="start_year" style="width:100px">
          <option value="StartingYear">Year</option>
          <option value="1854">1854</option>
          <option value="1855">1855</option>
          <option value="1856">1856</option>
          <option value="1857">1857</option>
          <option value="1858">1858</option>
          <option value="1859">1859</option>
          <option value="1860">1860</option>
          <option value="1861">1861</option>
          <option value="1862">1862</option>
          <option value="1863">1863</option>
          <option value="1864">1864</option>
          <option value="1865">1865</option>
          <option value="1866">1866</option>
          <option value="1867">1867</option>
          <option value="1868">1868</option>
          <option value="1869">1869</option>
          <option value="1870">1870</option>
          <option value="1871">1871</option>
          <option value="1872">1872</option>
          <option value="1873">1873</option>
          <option value="1874">1874</option>
          <option value="1875">1875</option>
          <option value="1876">1876</option>
          <option value="1877">1877</option>
          <option value="1879">1879</option>
          <option value="1880">1880</option>
        </select>
        to 
        <select name="end_year" style="width:100px">
          <option value="EndingYear">Year</option>
          <option value="1854">1854</option>
          <option value="1855">1855</option>
          <option value="1856">1856</option>
          <option value="1857">1857</option>
	  <option value="1858">1858</option>
	  <option value="1859">1859</option>
          <option value="1860">1860</option>
          <option value="1861">1861</option>
          <option value="1862">1862</option>
          <option value="1863">1863</option>
          <option value="1864">1864</option>
          <option value="1865">1865</option>
          <option value="1866">1866</option>
          <option value="1867">1867</option>
          <option value="1868">1868</option>
          <option value="1869">1869</option>
          <option value="1870">1870</option>
          <option value="1871">1871</option>
          <option value="1872">1872</option>
          <option value="1873">1873</option>
          <option value="1874">1874</option>
          <option value="1875">1875</option>
          <option value="1876">1876</option>
          <option value="1877">1877</option>
          <option value="1879">1879</option>
          <option value="1880">1880</option>
	</select>
        <hr style="width:600px; margin-left:0px">
        Journal
        <select name="journal" style="width:350px">
          <option value="JournalName">Journal Name</option>
	  <option value="Diary 1854-1861">Diary 1854-1861</option>
	  <option value="Diary 1861-1877">Diary 1861-1877</option>
          <option value="Diary 1879">Diary 1879</option>
          <option value="Diary 1880">Diary 1880</option>
          <option value="Germany 1858">Germany 1858</option>
          <option value="Italy 1864">Italy 1864</option>
          <option value="Journal to Normandy in 1865">Journal to Normandy in 1865</option>
          <option value="The Making of George Eliot 1857-1859">The Making of George Eliot 1857-1859</option>
	</select>
        <hr style="width:600px; margin-left:0px">
        <button type="submit">Search</button>
      </form>
    </div>
    <?php if ($_GET) { ?>
      <header>
        <h1><?php echo json_encode($url_params); ?></h1>
      </header>
      <?php
      if (count($years) > 0)
          require "multi_year_body.php";
      else { ?>
        <h3>No results found for your query.</h3>
      <?php } ?>
    <?php } ?>
  </body>
</html>
