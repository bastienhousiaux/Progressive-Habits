const mysql=require("mysql");

class QueryBuilder{
    
    constructor(database,host,user,password){
        this.database=database;
        this.host=host || 'localhost';
        this.user=user || 'root';
        this.password =password || '';
    }

    open(){
        this.connection = mysql.createConnection({
            host     : this.host,
            user     : this.user,
            password : this.password,
            database : this.database
        });

        this.connection.connect();
    }

    close(){
        this.connection.end((err)=>{
            if(err)console.log(err);
            else console.log("la connection s'est bien terminée");
        });
    }

    select(table,champs,where){

        champs=champs || ["*"];
        where=where || "1";
        this.connection.query("SELECT "+champs.join(",")+" FROM "+table+" WHERE " +where
        ,(error,results,fields)=>{
            console.log(results);
        });
    }
}

var qb=new QueryBuilder("notes_app");
qb.open();
qb.select("notes");

setTimeout(()=>{
    qb.select("notes",["*"],"id=1");
},2000);