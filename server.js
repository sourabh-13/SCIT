var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Sequelize = require('sequelize');
var dateFormat = require('dateformat');
var request   = require('request');
const Op        = Sequelize.Op; 

const WebsitePath = 'http://3.17.125.238/';
const sequelize = new Sequelize('scits', 'root', 'Scits@321', {
// const sequelize = new Sequelize('maktabsara', 'root', '', {
	host: 'localhost',
    dialect: 'mysql',
    operatorsAliases: false,
    define: {
        timestamps: false
    },
    pool: {
        max: 5,
        min: 0,
        acquire: 30000,
        idle: 10000
    },
});


app.get('/', function(req, res){
  res.send('SCITS');
});

http.listen(3015, function(){
  console.log('listening on *:3015');
});

const SULocationHistory = sequelize.define('su_location_history',{
	id:{
		type: Sequelize.BIGINT,
		primaryKey: true,
        autoIncrement: true
	},
	service_user_id:{
		type: Sequelize.INTEGER
	},
    latitude:{
		type: Sequelize.STRING
	},
    longitude:{
		type: Sequelize.STRING
	},
	name:{
		type: Sequelize.TEXT
	},
	location_type:{
        type: Sequelize.ENUM('A','R','G')
    },
    location_source:{
		type: Sequelize.ENUM('R','L')
    },
    old_location_type:{
		type: Sequelize.ENUM('A','R','G')
	},
	submission_type:{
		type: Sequelize.BOOLEAN
	},
    timestamp:{
        type: Sequelize.DATE
    },
},{tableName: 'su_location_history'});

io.on('connection', function(socket){
	console.log('connected');

 	socket.on('room join', function(data) { //when driver pickup order and tracking started 
        //console.log('room join', msg.room_id);
        socket.join(data.service_user_id, () => { console.log('room join');
            io.to(data.service_user_id).emit('room joined', data );
        }); 
    });
    
    socket.on('room leave',(data)=>{
        console.log('room leave', data.service_user_id);
        socket.leave(data.service_user_id, () => {
            io.to(data.service_user_id).emit('room leave',{ service_user_id:data.service_user_id });
        });
    })
    socket.on('new_location', function(data){
         console.log(data);
        if(data.service_user_id != null && data.name != null && data.latitude != null && data.longitude != null){
            // var current_date = dateFormat( Date(), "yyyy-mm-dd HH:MM:s");
            var current_date = dateFormat( Date(), "yyyy-mm-dd");
            // var current_date = dateFormat( Date(), "yyyy-mm-dd 01:01");
            // var current_date = new Date();
            // console.log(current_date);
            // if(current_date >= datetime){

            // }
            SULocationHistory.findOne({
            //     // where:{
            //     //     service_user_id: data.service_user_id,
            //     //     location_source: 'R',
            //     // },
            //     // where: sequelize.where(sequelize.fn('date', sequelize.col('timestamp')), current_date),

                where: {
                    [Op.and]: 
                    [
                        {

                            timestamp: sequelize.where(sequelize.fn('date', sequelize.col('timestamp')),'=',current_date),
                        },
                        {
                            service_user_id: data.service_user_id,
                            location_source: 'L',
                        }
                    ]
                },
            //     order:[['id','desc']],
            }).then(previous_location=>{
            //    // console.log(previous_location);
                if(previous_location== null){

                    SULocationHistory.create({
                        service_user_id: data.service_user_id,
                        latitude: data.latitude,
                        longitude: data.longitude,
                        name: data.name,
                        timestamp: new Date(),
                        submission_type: '0',
                        location_source: 'L'
                    }).then(data=>{
                        var options = {
                            url:WebsitePath+'api/service/location/alert'+'/'+data.id,
                            method:'get'                                                                                    
                        }
                        request(options, function(error, response, body){
                            if(!error && response.statusCode == 200){}
                        })
                    })
                }else{
                    SULocationHistory.create({
                        service_user_id: data.service_user_id,
                        latitude: data.latitude,
                        longitude: data.longitude,
                        name: data.name,
                        timestamp: new Date(),
                        submission_type: '0',
                        location_source: 'R'
                    }).then(data=>{
                        var options = {
                            url:WebsitePath+'api/service/location/alert'+'/'+data.id,
                            method:'get'                                                                                    
                        }
                        request(options, function(error, response, body){
                            if(!error && response.statusCode == 200){}
                        })
                    })
                }
                io.to(data.service_user_id).emit('new_location_got', data); ////to send to only those who has joined rooms
            })
        }
        // io.to(data.service_user_id).emit('new_location_got', data); ////to send to only those who has joined rooms
        // io.emit('new_location_got', data); //to send to all
    })
})
