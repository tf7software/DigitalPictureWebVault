<?php
include 'api-key.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors',0);
$page = $_GET['page'];

$swlink = 'https://vidsrc.me/movies/latest/page-'.$page.'.json';
$json_string = file_get_contents($swlink);
$parsed_json = json_decode($json_string, true);	
$parsed = $parsed_json['result'];

foreach($parsed as $key => $value)
{
	$imdb = $value['imdb_id'];					
	$tmdb = $value['tmdb_id'];					
	$title = $value['title'];
	$imdbembed = $value['embed_url'];
	$tmdbembed = $value['embed_url_tmdb'];

	$json = file_get_contents('http://api.themoviedb.org/3/find/'.$imdb.'?external_source=imdb_id&api_key='.$apikey);	
	$obj = json_decode($json, true);
	$poster = 'https://image.tmdb.org/t/p/w300'.$obj['movie_results']['0']["poster_path"];		
	if($poster == 'https://image.tmdb.org/t/p/w300') $poster = 'n/a';

	$rows[] = [
		'imdb_id' => $imdb,
		'tmdb_id' => $tmdb,
		'title' => $title,
		'imdb_embed' => $imdbembed,
		'tmdb_embed' => $tmdbembed,
		'poster' => $poster,
	];
}

$finaljson = json_encode($rows);
$finaljson = str_replace(array('vidsrc.me','movie?imdb=','movie?tmdb='),array('www.2embed.cc','',''),$finaljson);
echo $finaljson;
?>