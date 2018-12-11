

CREATE TABLE mx_user(
     DATA JSON, 
     mxp_id INT NOT NULL AUTO_INCREMENT, 
     mxp_data_id INT AS(json_extract(DATA, "$.mxp_id")) STORED, 
     PRIMARY KEY(mxp_id) 
);

CREATE TABLE mxp_subscription(
    id Int not null AUTO_INCREMENT,
    mxp_id INT (255),
    mxp_type INT,
    mxp_date VARCHAR(50),
    mxp_user_id VARCHAR(255),
    mxp_amount INT (20),
    mxp_medium Int (10),
    PRIMARY KEY(id) 
);

CREATE TABLE mxp_user(
    id Int not null AUTO_INCREMENT,
    mxp_user_id VARCHAR(255) AS(json_extract(DATA, "$.mxp_id")) STORED,
    mxp_aboutMe VARCHAR(255) AS(json_extract(DATA, "$.aboutMe")) STORED,
    mxp_avatar VARCHAR(255) AS(json_extract(DATA, "$.avatar")) STORED,
    mxp_city VARCHAR(50) AS(json_extract(DATA, "$.city")) STORED,
    mxp_country VARCHAR(255) AS(json_extract(DATA, "$.country")) STORED,
    mxp_dateOfBirth VARCHAR(25) AS(json_extract(DATA, "$.dateOfBirth")) STORED,
    mxp_displayName VARCHAR(255) AS(json_extract(DATA, "$.displayName")) STORED,
    mxp_encodedId VARCHAR(255) AS(json_extract(DATA, "$.encodedId")) STORED,
    mxp_fullName VARCHAR(255) AS(json_extract(DATA, "$.fullName")) STORED,
    mxp_firstName VARCHAR(255) AS(json_extract(DATA, "$.firstName")) STORED,
    mxp_lastName VARCHAR(255) AS(json_extract(DATA, "$.lastName")) STORED,
    mxp_email VARCHAR(255) AS(json_extract(DATA, "$.email")) STORED,
    mxp_gender INT (50) AS(json_extract(DATA, "$.gender")) STORED,
    mxp_memberSince VARCHAR(50) AS(json_extract(DATA, "$.memberSince")) STORED,
    mxp_state VARCHAR (25) AS(json_extract(DATA, "$.state")) STORED,
    mxp_accountType INT (10) AS(json_extract(DATA, "$.accountType")) STORED,
    mxp_verified INT (2) AS(json_extract(DATA, "$.verified")) STORED,
    mxp_status INT (2) AS(json_extract(DATA, "$.status")) STORED,
    mxp_phone VARCHAR(255) AS(json_extract(DATA, "$.phone")) STORED,
    mxp_connectIp VARCHAR(255) AS(json_extract(DATA, "$.connectIp")) STORED,
    DATA JSON,
    PRIMARY KEY(id)
);

INSERT INTO `mxp_user`(`DATA`) VALUES (
    '{
    "aboutMe": "",
    "avatar": "",
    "city": "Lagos",
    "country": "Nigeria",
    "dateOfBirth": "08/11/2018",
    "displayName": "",
    "distanceUnit": "",
    "encodedId": "",
    "fullName": "",
    "firstName": "",
    "lastName": "",
    "email": "",
    "gender": 0,
    "memberSince": "",
    "state": "",
    "accountType": 0,
    "verified": 0,
    "status": 0,
    "phone": "",
    "subscription": {
      "type": 0,
      "date": "",
      "amount": 0,
      "medium": 0
    },
    "connectIp": "",
    "myArtists": [],
    "myLikes": [],
    "myFavs": [],
    "myPlaylists": []
    }')


SELECT data from mxp_user;
SELECT data->"$.country" from mxp_user;
SELECT data->>"$.country" from mxp_user;


ALTER TABLE
    mx_user ADD COLUMN id INT AS(JSON_EXTRACT(DATA, "$.mxp_id")),
    ADD INDEX id_idx(id);




INSERT INTO `mx_user`(`DATA`) VALUES ( '{ "mx_id": "", "name": "Elyte", "type": "artist", "owner": "Musixplay", "aboutMe": "", "status":"", "avatar": "", "city": "", "country": "", "dateOfBirth": "", "displayName": "", "distanceUnit": "", "encodedId": "", "fullName": "", "firstName": "", "lastName": "", "email": "", "gender": "", "height": 0, "locale": "", "memberSince": "", "state": "", "accountType": "", "verified": true, "phone": "", "subscription": [], "connectIp": "", "myArtists": [], "myLikes": [] }' )