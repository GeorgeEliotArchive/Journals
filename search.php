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
 } else {
     $url_params = array(
         'keywords'   => '',
         'exact'      => '',
         'start_year' => '',
         'end_year'   => '',
         'journal'    => ''
     );
 }

 ?>
<html>
  <head>
    <title>Journal Search</title>
    <?php require "common_head.php"; ?>
    <style type="text/css">
      .topnav {
          margin-left: 10px;
          //margin-top: 30px;
          width: 100%;
      }
    </style>
  </head>
  <body>
    <div class="topnav">
      <h1>Search Options</h1>
      <h3>Search for journal entries with:</h3>
      <hr style="width:600px; margin-left:0px">
      <form action="./search.php" method="get">
        Any of these Keywords: <?php echo
     '<input name="keywords" type="text" value="'.$url_params["keywords"].'">';
                   ?>.<br>
        <hr style="width:600px; margin-left:0px">
        This exact text: <?php echo
     '<input name="exact" type="text" value="'.$url_params["exact"].'">';
                        ?>.<br>
        <hr style="width:600px; margin-left:0px">
        In the years from 
        <select name="start_year" style="width:100px">
          <option value="1854" <?php if($url_params['start_year']=="1854"||$url_params['start_year']=='')echo'selected';?>>1854</option>
          <option value="1855" <?php if($url_params['start_year']=="1855")echo'selected';?>>1855</option>
          <option value="1856" <?php if($url_params['start_year']=="1856")echo'selected';?>>1856</option>
          <option value="1857" <?php if($url_params['start_year']=="1857")echo'selected';?>>1857</option>
          <option value="1858" <?php if($url_params['start_year']=="1858")echo'selected';?>>1858</option>
          <option value="1859" <?php if($url_params['start_year']=="1859")echo'selected';?>>1859</option>
          <option value="1860" <?php if($url_params['start_year']=="1860")echo'selected';?>>1860</option>
          <option value="1861" <?php if($url_params['start_year']=="1861")echo'selected';?>>1861</option>
          <option value="1862" <?php if($url_params['start_year']=="1862")echo'selected';?>>1862</option>
          <option value="1863" <?php if($url_params['start_year']=="1863")echo'selected';?>>1863</option>
          <option value="1864" <?php if($url_params['start_year']=="1864")echo'selected';?>>1864</option>
          <option value="1865" <?php if($url_params['start_year']=="1865")echo'selected';?>>1865</option>
          <option value="1866" <?php if($url_params['start_year']=="1866")echo'selected';?>>1866</option>
          <option value="1867" <?php if($url_params['start_year']=="1867")echo'selected';?>>1867</option>
          <option value="1868" <?php if($url_params['start_year']=="1868")echo'selected';?>>1868</option>
          <option value="1869" <?php if($url_params['start_year']=="1869")echo'selected';?>>1869</option>
          <option value="1870" <?php if($url_params['start_year']=="1870")echo'selected';?>>1870</option>
          <option value="1871" <?php if($url_params['start_year']=="1871")echo'selected';?>>1871</option>
          <option value="1872" <?php if($url_params['start_year']=="1872")echo'selected';?>>1872</option>
          <option value="1873" <?php if($url_params['start_year']=="1873")echo'selected';?>>1873</option>
          <option value="1874" <?php if($url_params['start_year']=="1874")echo'selected';?>>1874</option>
          <option value="1875" <?php if($url_params['start_year']=="1875")echo'selected';?>>1875</option>
          <option value="1876" <?php if($url_params['start_year']=="1876")echo'selected';?>>1876</option>
          <option value="1877" <?php if($url_params['start_year']=="1877")echo'selected';?>>1877</option>
          <option value="1879" <?php if($url_params['start_year']=="1879")echo'selected';?>>1879</option>
          <option value="1880" <?php if($url_params['start_year']=="1880")echo'selected';?>>1880</option>
        </select>
        to 
        <select name="end_year" style="width:100px">
          <option value="1854" <?php if($url_params['end_year']=="1854")echo'selected';?>>1854</option>
          <option value="1855" <?php if($url_params['end_year']=="1855")echo'selected';?>>1855</option>
          <option value="1856" <?php if($url_params['end_year']=="1856")echo'selected';?>>1856</option>
          <option value="1857" <?php if($url_params['end_year']=="1857")echo'selected';?>>1857</option>
          <option value="1858" <?php if($url_params['end_year']=="1858")echo'selected';?>>1858</option>
          <option value="1859" <?php if($url_params['end_year']=="1859")echo'selected';?>>1859</option>
          <option value="1860" <?php if($url_params['end_year']=="1860")echo'selected';?>>1860</option>
          <option value="1861" <?php if($url_params['end_year']=="1861")echo'selected';?>>1861</option>
          <option value="1862" <?php if($url_params['end_year']=="1862")echo'selected';?>>1862</option>
          <option value="1863" <?php if($url_params['end_year']=="1863")echo'selected';?>>1863</option>
          <option value="1864" <?php if($url_params['end_year']=="1864")echo'selected';?>>1864</option>
          <option value="1865" <?php if($url_params['end_year']=="1865")echo'selected';?>>1865</option>
          <option value="1866" <?php if($url_params['end_year']=="1866")echo'selected';?>>1866</option>
          <option value="1867" <?php if($url_params['end_year']=="1867")echo'selected';?>>1867</option>
          <option value="1868" <?php if($url_params['end_year']=="1868")echo'selected';?>>1868</option>
          <option value="1869" <?php if($url_params['end_year']=="1869")echo'selected';?>>1869</option>
          <option value="1870" <?php if($url_params['end_year']=="1870")echo'selected';?>>1870</option>
          <option value="1871" <?php if($url_params['end_year']=="1871")echo'selected';?>>1871</option>
          <option value="1872" <?php if($url_params['end_year']=="1872")echo'selected';?>>1872</option>
          <option value="1873" <?php if($url_params['end_year']=="1873")echo'selected';?>>1873</option>
          <option value="1874" <?php if($url_params['end_year']=="1874")echo'selected';?>>1874</option>
          <option value="1875" <?php if($url_params['end_year']=="1875")echo'selected';?>>1875</option>
          <option value="1876" <?php if($url_params['end_year']=="1876")echo'selected';?>>1876</option>
          <option value="1877" <?php if($url_params['end_year']=="1877")echo'selected';?>>1877</option>
          <option value="1879" <?php if($url_params['end_year']=="1879")echo'selected';?>>1879</option>
          <option value="1880" <?php if($url_params['end_year']=="1880"||$url_params['end_year']=='')echo'selected';?>>1880</option>
        </select>.
        <hr style="width:600px; margin-left:0px">
        From the following source:
        <select name="journal" style="width:350px">
          <option <?php if($url_params['journal']=="")echo'selected';?> value="">All Journals</option>
          <option <?php if($url_params['journal']=="Diary 1854-1861")echo'selected';?> value="Diary 1854-1861">Diary 1854-1861</option>
          <option <?php if($url_params['journal']=="Diary 1861-1877")echo'selected';?> value="Diary 1861-1877">Diary 1861-1877</option>
          <option <?php if($url_params['journal']=="Diary 1879")echo'selected';?> value="Diary 1879">Diary 1879</option>
          <option <?php if($url_params['journal']=="Diary 1880")echo'selected';?> value="Diary 1880">Diary 1880</option>
          <option <?php if($url_params['journal']=="Germany 1858")echo'selected';?> value="Germany 1858">Germany 1858</option>
          <option <?php if($url_params['journal']=="Italy 1864")echo'selected';?> value="Italy 1864">Italy 1864</option>
          <option <?php if($url_params['journal']=="Journal to Normandy in 1865")echo'selected';?> value="Journal to Normandy in 1865">Journal to Normandy in 1865</option>
          <option <?php if($url_params['journal']=="The Making of George Eliot 1857-1859")echo'selected';?> value="The Making of George Eliot 1857-1859">The Making of George Eliot 1857-1859</option>
        </select>.
        <hr style="width:600px; margin-left:0px">
        <button type="submit">Search</button>
        <button type="reset">Reset Search Options</button>
      </form>
      <hr>
    </div>
    <?php if ($_GET) { ?>
      <header>
        <h1>Search Results</h1>
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
