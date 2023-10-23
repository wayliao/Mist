    DROP DATABASE IF EXISTS mist;
    CREATE DATABASE mist;

    USE mist;

    CREATE TABLE users (
        userID                  INT                 NOT NULL                AUTO_INCREMENT,
        userFirstName           VARCHAR(16),
        userLastName            VARCHAR(16),
        userEmail               VARCHAR(64),
        userPassword            VARCHAR(128),
        userDate                DATE,
        userRole                VARCHAR(16),

        PRIMARY KEY (userID)
    );

    CREATE TABLE companies (
        companyID               INT                 NOT NULL                AUTO_INCREMENT,
        companyName             VARCHAR(64),
        companyDescription      VARCHAR(1028),
        companyLink             VARCHAR(64),

        PRIMARY KEY (companyID)
    );

    CREATE TABLE requests (
        requestID               INT                 NOT NULL                AUTO_INCREMENT,
        userID                  INT,
        requestAction           VARCHAR(16),
        requestReason           VARCHAR(64),

        PRIMARY KEY (requestID),
        FOREIGN KEY (userID)                        REFERENCES users(userID)
    );

    CREATE TABLE games (
        gameID                  INT                 NOT NULL                AUTO_INCREMENT,
        companyID               INT,
        requestID               INT,
        gameName                VARCHAR(64),
        gameDescription         VARCHAR(1028),
        gameGenre               VARCHAR(16),
        gameDate                DATE,
        gamePicture             LONGBLOB,
        compatibleWindows       TINYINT(1),
        compatibleMacOS         TINYINT(1),
        compatibleLinux         TINYINT(1),

        PRIMARY KEY (gameID),
        FOREIGN KEY (companyID)                     REFERENCES companies(companyID),
        FOREIGN KEY (requestID)                     REFERENCES requests(requestID)
    );

    CREATE TABLE posts (
        postID                  INT                 NOT NULL                AUTO_INCREMENT,
        userID                  INT,
        postName                VARCHAR(64),
        postDescription         VARCHAR(1028),
        postLikes               INT,
        postDate                DATE,
        postDeleted             TINYINT(1),

        PRIMARY KEY (postID),
        FOREIGN KEY (userID)                        REFERENCES users(userID)
    );

    DELIMITER $$

    CREATE PROCEDURE spCreateUser (
        IN spUserFirstName VARCHAR(16),
        IN spUserLastName VARCHAR(16),
        IN spUserEmail VARCHAR(64),
        IN spUserPassword VARCHAR(128)
    )

    BEGIN
        INSERT INTO users (
            userFirstName,
            userLastName,
            userEmail,
            userPassword,
            userDate
        ) VALUES (
            spUserFirstName,
            spUserLastName,
            spUserEmail,
            spUserPassword,
            CAST(NOW() AS Date)
        );
    END

    $$

    CREATE PROCEDURE spCreateRequest (
        IN      spUserID                            INT,
        IN      spGameName                          VARCHAR(64),
        IN      spGameDescription                   VARCHAR(1028),
        IN      spGameGenre                         VARCHAR(16),
        IN      spGamePicture                       LONGBLOB,
        IN      spCompatibleWindows                 TINYINT(1),
        IN      spCompatibleMacOS                   TINYINT(1),
        IN      spCompatibleLinux                   TINYINT(1)
    )

    BEGIN
        INSERT INTO requests (
            userID,
            requestAction,
            RequestReason
        ) VALUES (
            spUserID,
            "pending",
            "pending"
        );

        INSERT INTO games (
            requestID,
            gameName,
            gameDescription,
            gameGenre,
            gameDate,
            gamePicture,
            compatibleWindows,
            compatibleMacOS,
            compatibleLinux
        ) VALUES (
            LAST_INSERT_ID(),
            spGameName,
            spGameDescription,
            spGameGenre,
            CAST(NOW() AS Date),
            spGamePicture,
            spCompatibleWindows,
            spCompatibleMacOS,
            spCompatibleLinux
        );
        
        SELECT
            LAST_INSERT_ID() AS gameID;
    END

    $$

    CREATE PROCEDURE spUpdateRequest (
        IN spRequestID INT,
        IN spRequestAction VARCHAR(16),
        IN spRequestReason VARCHAR(64)
    )

    BEGIN
        UPDATE
            requests
        SET
            requestAction = spRequestAction,
            requestReason = spRequestReason
        WHERE
            requestID = spRequestID;
    END

    $$

    CREATE PROCEDURE spCreatePost (
        IN spUserID INT,
        IN spPostName VARCHAR(64),
        IN spPostDescription VARCHAR(1028)
    )

    BEGIN
        INSERT INTO posts (
            userID,
            postName,
            postDescription,
            postLikes,
            postDate,
            postDeleted
        ) VALUES (
            spUserID,
            spPostName,
            spPostDescription,
            0,
            CAST(NOW() AS Date),
            0
        );
    END

    $$

    CREATE PROCEDURE spDeletePost (
        IN spPostID INT
    )

    BEGIN
        UPDATE
            posts
        SET
            postDeleted = 1
        WHERE
            postID = spPostID;
    END
    
    $$

    CREATE PROCEDURE spGetGameFromID (
        IN spGameID INT
    )

    BEGIN
        SELECT
            gameID,
            games.companyID AS companyID,
            games.requestID AS requestID,
            gameName,
            IFNULL(companyName, CONCAT(userFirstName, " ", userLastName)) AS developerName,
            gameDescription,
            gameGenre,
            gameDate,
            gamePicture,
            compatibleWindows,
            compatibleMacOS,
            compatibleLinux
        FROM
            games
            LEFT JOIN   companies       ON      games.companyID = companies.companyID
            LEFT JOIN   requests        ON      games.requestID = requests.requestID
            LEFT JOIN   users           ON      requests.userID = users.userID
        WHERE
            gameID = spGameID;
    END
            
    $$

    CREATE PROCEDURE spGetRequestFromID (
        IN spRequestID INT
    )

    BEGIN
        SELECT
            requestID,
            userID,
            requestAction,
            requestReason
        FROM
            requests
        WHERE
            requestID = spRequestID;
    END       

    $$

    CREATE PROCEDURE spGetUserFromID (
        IN spUserID INT
    )

    BEGIN
        SELECT
            userID,
            CONCAT(userFirstName, " ", userLastName) AS userName,
            userEmail,
            userPassword,
            userDate,
            IFNULL(userRole, "user") AS userRole
        FROM
            users
        WHERE
            userID = spUserID;
    END       

    $$

    CREATE PROCEDURE spGetPostFromID (
        IN spPostID INT
    )

    BEGIN
        SELECT
            postID,
            posts.userID,
            CONCAT(userFirstName, " ", userLastName) AS postAuthor,
            postName,
            postDescription,
            postLikes,
            postDate,
            postDeleted
        FROM
            posts
            LEFT JOIN   users      ON          posts.userID = users.userID
        WHERE
            postID = spPostID;
    END
    
    $$

    CREATE PROCEDURE spGetGamesFromSearch (
        IN spSearch VARCHAR(64),
        IN spSort VARCHAR(16),
        IN spGenre VARCHAR(16),
        IN spRequestAction VARCHAR(16)
    )

    BEGIN
        SELECT
            gameID
        FROM
            games
            LEFT JOIN   requests        ON      games.requestID = requests.requestID
        WHERE
                gameName LIKE CONCAT('%', spSearch, '%')
            AND
                gameGenre = IF(spGenre != "", spGenre, gameGenre)        
            AND
                CASE
                    WHEN spRequestAction = "accepted"
                    THEN requestAction = spRequestAction OR companyID IS NOT NULL

                    WHEN spRequestAction = "pending"
                    THEN requestAction = spRequestAction
                END
        ORDER BY
            CASE
                WHEN spSort = "atoz"
                THEN gameName

                WHEN spSort = "oldest"
                THEN gameDate
            END ASC,
            
            CASE
                WHEN spSort = "ztoa"
                THEN gameName

                WHEN spSort = "newest"
                THEN gameDate
            END DESC;
    END

    $$

    CREATE PROCEDURE spGetPostsFromSearch (
        IN spSearch VARCHAR(64),
        IN spSort VARCHAR(16)
    )

    BEGIN
        SELECT
            postID
        FROM
            posts
        WHERE
            postName LIKE CONCAT('%', spSearch, '%') AND
            postDeleted = 0
        ORDER BY
            CASE
                WHEN spSort = "date"
                THEN postDate
            
                WHEN spSort = "likes"
                THEN postLikes
                
                ELSE postDate
            END DESC;
    END
    
    $$

    CREATE PROCEDURE spAddLike (
        IN spPostID INT
    )

    BEGIN
        UPDATE
            posts
        SET
            postLikes = postLikes + 1
        WHERE
            postID = spPostID;
    END
    
    $$

    CREATE PROCEDURE spGetUserFromEmail (
        IN spUserEmail VARCHAR(64)
    )

    BEGIN
        SELECT
            *
        FROM
            users
        WHERE
            userEmail = spUserEmail;
    END

    $$

    DELIMITER ;
