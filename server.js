const express=require("express");

const app=express();
console.log("app");
require("./routes");

app.listen(3000,()=>{
    console.log("server started on port 3000");
});