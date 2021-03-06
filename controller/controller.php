<?php
ini_set('display_errors','on');

$app->get('/v1/fetch/userdata/{id}',function($request, $response, $args) {
	$id = $args["id"];
    // $sql = 'SELECT * from mxp_user where id='.$id;
    // $db = $this->db;
	// $rs=$db->query('SELECT * from mxp_user where id='.$id);
    // $arr = $rs->fetch_all(MYSQLI_ASSOC);
    
    $stmt = $this->db->prepare('SELECT * from mxp_user where id='.$id);
    $stmt->execute();
    if ($data = $stmt->fetchAll()) {
        //print_r(json_encode($data));
        return json_encode($data, true);
        // return json_encode(array(
        //     "error" => 0,
        //     "message" => "User data fetch successfully",
        //         "users" => $data
        //   ));
    } else {
        return json_encode(array(
            "error" => 404,
            "message" => "User data fetch failed"
          ));
    }
});

$app->get('/v1/login',function() use($app) {
    $req = $app->request();
    $requiredfields = array(
        'email',
        'password'
    );
  
    // validate required fields
    if(!RequiredFields($req->get(), $requiredfields)){
        return false;
    }

	$email = $req->get("email");
	$password = $req->get("password");
	global $conn;
	$sql='SELECT * from users where EmailAddress="'.$email.'" and Password="'.$password.'"';
	$rs=$conn->query($sql);
    $arr = $rs->fetch_array(MYSQLI_ASSOC);
    
    if($arr == null){
            echo json_encode(array(
                "error" => 1,
                "message" => "Email-id or Password doesn't exist",
            ));
            return;
        }
        
    echo json_encode(array(
        "error" => 0,
        "message" => "User logged in successfully",
            "users" => $arr
    ));
});


//ARTIST
//create new artist
$app->post('/v1/create/artist/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $name = $request->getParsedBody()["name"];
    $label = $request->getParsedBody()["label"];
    $des = $request->getParsedBody()["des"];
    $cover = $request->getParsedBody()["cover"];
    $active = $request->getParsedBody()["active"];
    if (($name != null) && ($label != null) && ($des != null) && ($cover != null)) {
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_artist (name,label,des,cover,active) VALUES(:name,:label,:des,:cover,:active)');
        $sth->bindValue("name", $name, PDO::PARAM_STR);
        $sth->bindValue("label", $label, PDO::PARAM_STR);
        $sth->bindParam("des", $des, PDO::PARAM_STR);
        $sth->bindParam("cover", $cover, PDO::PARAM_STR);
        $sth->bindParam("active", $active);
        if ($sth->execute()) {
            return $response -> write("artist created successfully.");
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "artisrt creation error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});

// get all artists
$app->get('/v1/getArtists', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM orin_artist ORDER BY id");
    $sth->execute();
    $artists = $sth->fetchAll();
    return $this->response->withJson($artists);
});



//TRACKS
//create new track
$app->post('/v1/create/track/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $name = $request->getParsedBody()["name"];
    $artist_id = $request->getParsedBody()["artist_id"];
    $cover = $request->getParsedBody()["cover"];
    $year = $request->getParsedBody()["year"];
    $label = $request->getParsedBody()["label"];
    $type = $request->getParsedBody()["type"];
    $des = $request->getParsedBody()["des"];
    $active = $request->getParsedBody()["active"];
    if (($name != null) && ($label != null) && ($des != null) && ($cover != null)) {
        $input = $request->getParsedBody();
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_track (name,artist_id,cover,year,label,type,des,active) VALUES(:name,:artist_id,:cover,:year,:label,:type,:des,:active)');
        $sth->bindValue("name", $name, PDO::PARAM_STR);
        $sth->bindParam("artist_id", $artist_id, PDO::PARAM_STR);
        $sth->bindParam("cover", $cover, PDO::PARAM_STR);
        $sth->bindValue("year", $year, PDO::PARAM_STR);
        $sth->bindParam("label", $label, PDO::PARAM_STR);
        $sth->bindParam("type", $type);
        $sth->bindParam("des", $des, PDO::PARAM_STR);
        $sth->bindParam("active", $active);
        if ($sth->execute()) {
            //return $response -> write("Track created successfully.");
            $input['id'] = $this->db->lastInsertId();
            return $this->response->withJson($input);
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "Track creation error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});

// get all tracks
$app->get('/v1/getTracks', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM orin_track ORDER BY id");
    $sth->execute();
    $tracks = $sth->fetchAll();
    return $this->response->withJson($tracks);
});

// Retrieve track with id 
$app->get('/v1/getTrack/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM orin_track WHERE id=:id");
   $sth->bindParam("id", $args['id']);
   $sth->execute();
   $track = $sth->fetchObject();
   return $this->response->withJson($track);
});

// Search for todo with given search teram in their name
$app->get('/v1/track/search/[{query}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM orin_track WHERE UPPER(name) LIKE :query ORDER BY name");
    $query = "%".$args['query']."%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $tracks = $sth->fetchAll();
    return $this->response->withJson($tracks);
});

// Update track with given id
$app->put('/v1/track/update/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE orin_track SET name=:name,artist_id=:artist_id,cover=:cover,year=:year,label=:label,type=:type,des=:des,active=:active  WHERE id=:id";
     $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindValue("name", $input['name'], PDO::PARAM_STR);
    $sth->bindParam("artist_id", $input['artist_id'], PDO::PARAM_STR);
    $sth->bindParam("cover", $input['cover'], PDO::PARAM_STR);
    $sth->bindValue("year", $input['year'], PDO::PARAM_STR);
    $sth->bindParam("label", $input['label'], PDO::PARAM_STR);
    $sth->bindParam("type", $input['type']);
    $sth->bindParam("des", $input['des'], PDO::PARAM_STR);
    $sth->bindParam("active", $input['active']);
    $sth->execute();
    $input['id'] = $args['id'];
    return $this->response->withJson($input);
});









//ALBUM
$app->post('/v1/create/album/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $name = $request->getParsedBody()["name"];
    $artist_id = $request->getParsedBody()["artist_id"];
    $cover = $request->getParsedBody()["cover"];
    $des = $request->getParsedBody()["des"];
    $type = $request->getParsedBody()["type"];
    $child_id = $request->getParsedBody()["track"];
    $child_type = $request->getParsedBody()["track_type"];
    $active = $request->getParsedBody()["active"];
    if (($name != null) && ($artist_id != null) && ($des != null) && ($cover != null)) {
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_album (name,artist_id,cover,des,type,child_id,child_type,active) VALUES(:name,:artist_id,:cover,:des,:type,:child_id,:child_type,:active)');
        $sth->bindValue("name", $name, PDO::PARAM_STR);
        $sth->bindParam("artist_id", $artist_id, PDO::PARAM_STR);
        $sth->bindParam("cover", $cover, PDO::PARAM_STR);
        $sth->bindParam("des", $des, PDO::PARAM_STR);
        $sth->bindParam("type", $type);
        $sth->bindValue("child_id", $child_id, PDO::PARAM_STR);
        $sth->bindParam("child_type", $child_type, PDO::PARAM_STR);
        $sth->bindParam("active", $active);
        if ($sth->execute()) {
            return $response -> write("Album created successfully.");
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "Album creation error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});


//PLAYLIST
$app->post('/v1/create/playlist/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $name = $request->getParsedBody()["name"];
    $cover = $request->getParsedBody()["cover"];
    $year = $request->getParsedBody()["year"];
    $type = $request->getParsedBody()["type"];
    $child_id = $request->getParsedBody()["track"];
    $child_type = $request->getParsedBody()["track_type"];
    $active = $request->getParsedBody()["active"];
    if (($name != null) && ($type != null) && ($active != null) && ($cover != null)) {
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_playlist (name,cover,year,type,child_id,child_type,active) VALUES(:name,:cover,:year,:type,:child_id,:child_type,:active)');
        $sth->bindValue("name", $name, PDO::PARAM_STR);
        $sth->bindParam("cover", $cover, PDO::PARAM_STR);
        $sth->bindParam("year", $year, PDO::PARAM_STR);
        $sth->bindParam("type", $type);
        $sth->bindValue("child_id", $child_id, PDO::PARAM_STR);
        $sth->bindParam("child_type", $child_type, PDO::PARAM_STR);
        $sth->bindParam("active", $active);
        if ($sth->execute()) {
            return $response -> write("Playlist created successfully.");
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "Playlist creation error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});

//LISTEN
$app->post('/v1/listen/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $child_id = $request->getParsedBody()["child_id"];
    $child_type = $request->getParsedBody()["child_type"];
    if (($child_id != null) && ($child_type != null)) {
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_listen (child_id,child_type) VALUES(:child_id,:child_type)');
        $sth->bindValue("child_id", $child_id, PDO::PARAM_STR);
        $sth->bindParam("child_type", $child_type, PDO::PARAM_STR);
        if ($sth->execute()) {
            return $response -> write("Number of play/listen updated successfully.");
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "Number of play/listen update error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});

//DOWNLOAD
$app->post('/v1/download/{id}', function($request, $response, $args) {
    $id = $args['id'];
    $child_id = $request->getParsedBody()["child_id"];
    $child_type = $request->getParsedBody()["child_type"];
    if (($child_id != null) && ($child_type != null)) {
        // INSERT INTO orin_artist ( id , name , label, des, cover ) VALUES ( ? , ? , ? )
        $sth = $this->db->prepare('INSERT INTO orin_download (child_id,child_type) VALUES(:child_id,:child_type)');
        $sth->bindValue("child_id", $child_id, PDO::PARAM_STR);
        $sth->bindParam("child_type", $child_type, PDO::PARAM_STR);
        if ($sth->execute()) {
            return $response -> write("Number of downloads updated successfully.");
        } else {
            return json_encode(array(
                "error" => 99,
                "message" => "Number of downloads update error!",
            ));
        }
        //$todos = $sth->fetchObject();
        //return $this->response->withJson($todos);
    } else {
        return json_encode(array(
            "error" => 1,
            "message" => "Your request format is incorrect!",
        ));
        //return json_encode($data, true);
        //return $response -> write("Your request format is incorrect!");
    }
});

?>
