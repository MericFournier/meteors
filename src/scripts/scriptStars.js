var starfield = null; //initialisation de la variable starfield

//La méthode window.requestAnimationFrame() notifie le navigateur que vous souhaitez exécuter une animation et demande que celui-ci exécute une fonction spécifique de mise à jour de l'animation, avant le prochain rafraîchissement du navigateur. Cette méthode prend comme argument un callback qui sera appelé avant le rafraîchissement du navigateur.
window.requestAnimationFrame = 	window.requestAnimationFrame ||         window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

window.onload = function()
{
	starfield = new jsStarfield; //appel d'un nouvel objet jsStarfield
	starfield.init("main_canvas"); //appel de la méthode init de l'objet jsStarfield
	
//    //interface pour modifier les valeurs des variables
//	var gui = new dat.GUI();
//    
//    //nombre d'étoile entre 0 et 200 000
//	gui.add(starfield, 'amount', 0, 200000).onFinishChange(function(value)
//	{
//		starfield.set_amount(value);
//	});
//    
//    //vitesse entre 0 et 1500
//	gui.add(starfield, 'speed', 0, 1500);
//    
//    //
//	gui.add(starfield, 'follow_mouse', 0, 2500).onFinishChange(function(value)
//	{
//		if (value === false)
//			starfield.reset_origin();
//	})
//    
//    //choix entre rects et buffer
//	gui.add(starfield, 'method', [ 'rects', 'buffer'] );
	
    window.addEventListener('wheel', function(event){
        event.preventDefault();
        
        if(roue = new WheelEvent("deltaY") > 0){
            starfield.speed = 100;
            starfield.draw_rects();
            console.log('up');
        }
        else{
            starfield.speed = 100;
            starfield.draw_back_rects();
            console.log('down');
        }
        
//        if(starfield.speed == 900){
//            starfield.speed = 0;
//            console.log('stop');
//        }
//        else{
//            starfield.speed = 900;
//            console.log('gooo');
//        }
    });
    
    var syntheticEvent = new WheelEvent("syntheticWheel", {"deltaY": 4, "deltaMode": 0});

console.log(syntheticEvent.deltaY);
    
    //appel de la fonction de _loop_ qui permet de mettre à jour l'animation
	_loop_();
};

//si la taille de la fenêtre change alors on modifie la taille du "champs" d'étoile
window.addEventListener('resize', function()
{
	starfield.resize(window.innerWidth, window.innerHeight);
});

//réinitialisation de la fonction _update_
function _loop_()
{
	anim_id = window.requestAnimationFrame( _update_ );
}

//appel de la fonction _loop_ qui permet de réinitialiser l'animation
function _update_(time)
{
	starfield.update(time);
	_loop_();
}

function Vector2(x, y)
{
	this.x = x;
	this.y = y;
}

function Vector3(x, y, z)
{
	this.x = x;
	this.y = y;
	this.z = z;
}

//on calcule une valeur aléatoire entre une valeur minimale et une valeur maximale
// valeur aléatoire comprise dans l'interval [min, max]
function rand_range(min, max) { return min + Math.random() * (max-min); }

//création de l'objet jsStarfield
function jsStarfield()
{
	this.stars = []; //propriété stars qui es un tableau
	
	this.max_depth = 1000; //propriété profondeur maximale
	this.max_size = 3; //propriété taille maximale
//	this.speed = 750; //propriété vitesse initiale
    
	this.speed = 0; //propriété vitesse initiale
    
	this.amount = 1000; //propriété nombre d'étoile au départ
	
//	this.follow_mouse = false; //au début les étoiles ne suivent pas la souris
	this.method = "rects"; //initialisation de la propriété method à rects
	
	this.last_frame = 0;
	this.fps_time = 0;
	this.fps_count = 0;
	this.fps = 0;
	
	this.origin = new Vector2(0, 0); //initialisation d'une position x, y à 0
	
	this.init = function(canvas) //initialisation du canvas
	{
		this.canvas = document.getElementById(canvas); //récupération du canvas dans le dom
		this.ctx = this.canvas.getContext('2d'); //on utilise ctx pour dessiner
        
		this.resize(window.innerWidth, window.innerHeight); //appel de la fonction resize avec comme paramètre la hauteur et la largeur de la fenêtre
		this.ctx.font="18px Arial";	//nom de la police et sa taille 			
		
		this.reset_origin(); //appel de la fonction reset_origin qui repositionne les x et y du nouveau vecteur au milieu de la fenêtre
		
        //à chaque fois qu'on bouge la souris on vérifie l'état de la propriété follow_mouse du nouvel objet jsStarfield
		this.canvas.addEventListener('mousemove', function(event) 
		{
            //si l'état est true alors on suit les mouvements de la souris
			if (starfield.follow_mouse == true) 
				starfield.set_origin(event.x, event.y);
		});
		
		// clear the array
		this.stars.length = 0;
		this.init_stars(); //appel de la fonction init_stars qui crée autant d'étoile que préciser dans la propriété amount
    };
	
	this.set_amount = function(amount)
    {
		this.amount = Math.floor(amount); //renvoie un entier d'étoile
		
        //si le nombre d'étoile demandée est inférieur au nombre d'étoile du tableau stars alors la longueur du tableau est égale au nombre d'étoile demandée (en gros on supprime les étoiles en trop du tableau)
		if (this.amount < this.stars.length)
		{
			this.stars.length = this.amount;
		}
        // sinon on en crée jusqu'à arriver au nombre demandé
		else
		{
			var amt = this.amount - this.stars.length;
			
            //positionnement aléatoire des étoiles suivant x, y et z
			for (var i=0; i<amt; i++)
            {
				this.stars.push(new Vector3(
                    rand_range(-this.canvas.width,this.canvas.width), rand_range(-this.canvas.height,this.canvas.height), rand_range(1, this.max_depth)) 
                );
            }
	};
    }
	this.init_stars = function()
	{
		// initialise le nombre d'étoile suivant le nombre demandé
		for (var i=0 ; i<this.amount ; i++)
        {
            //création d'une étoile dans le tableau avec des positionnements aléatoires
            //on a mis des "-" dans la fonction rand_range car on initialise la position des étoiles au centre de la fenêtre
			this.stars.push(new Vector3(
                rand_range(-this.canvas.width,this.canvas.width), rand_range(-this.canvas.height,this.canvas.height), rand_range(1, this.max_depth)) 
            );	
	   };
    }
	
	this.update = function(time)
	{
		var delta_time = (time - this.last_frame)*0.001;
		this.update_stars(delta_time);
		
		if (this.method === "rects")
			this.draw_rects();
		else
			this.draw_buffer();
			
		this.draw_fps(delta_time);
			
		this.last_frame = time;
	};	
	
	this.update_stars = function(delta_time)
	{
		var distance = this.speed * delta_time; //v=d/t => d=v/t
		
		for (var i=0; i<this.stars.length; i++)
		{
			var star = this.stars[i];
			
			star.z -= distance;
			
			if (star.z <= 0)
			{
				star.x = rand_range(-this.canvas.width,this.canvas.width);
				star.y = rand_range(-this.canvas.height,this.canvas.height);
				star.z = this.max_depth;
			}
		}	
	};
	
    //modification de la taille du canvas suivant la taille de la fenêtre
	this.resize = function(width, height)
	{
		this.canvas.width = width;
		this.canvas.height = height;
		
		this.canvas.style.width = width + "px";
		this.canvas.style.height = height + "px";
		
		// get the buffer
		this.img_data = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height); //permet de récupérer les pixels du canvas
		
		this.reset_origin();
	};
	
    //permet de créer le sentiment de profondeur
    //on déplace les étoiles en x et y suivant l'axe z : moins on est profond plus l'étoile est éloignée du centre
	this.draw_rects = function()
	{
        //on "découpe" un rectangle blanc
		this.ctx.fillStyle = 'rgb(255,255,255)';
		this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); //efface la traine des étoiles
		
		for (var i=0; i<this.stars.length; i++)
		{
			var star = this.stars[i]; // on récupère les étoiles 1 par 1
			var k = 256 / star.z; //256 divisé par la profondeur des étoiles
			var x = star.x*k + this.origin.x;
			var y = star.y*k + this.origin.y;
			
            //augmente la taille des étoiles (rectangle)
			var size = ((this.max_depth-star.z)/this.max_depth) * this.max_size;
			this.ctx.fillRect(x, y, size, size);
		}	
	};
    
    this.draw_back_rects = function()
	{
        //on "découpe" un rectangle blanc
		this.ctx.fillStyle = 'rgb(255,255,255)';
		this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); //efface la traine des étoiles
		
		for (var i=0; i<this.stars.length; i++)
		{
			var star = this.stars[i]; // on récupère les étoiles 1 par 1
			var k = 256 / star.z; //256 divisé par la profondeur des étoiles
			var x = this.origin.x - star.x*k;
			var y = this.origin.y - star.y*k;
			
            //augmente la taille des étoiles (rectangle)
			var size = ((1+star.z)/this.max_depth) * this.max_size;
			this.ctx.fillRect(x, y, size, size);
		}	
	};
	
//	this.draw_buffer = function()
//	{
//		var pos = 0, x, y;
//        
//        //taille du tableau des données des pixels
//		var length = this.img_data.data.length;
//		var width = this.img_data.width*4;
//		
//		for ( pos = 0; pos < length ; pos++ )
//			this.img_data.data[pos] = 0;
//		
//		for (var i=0; i<this.stars.length; i++)
//		{
//			var star = this.stars[i];
//
//			var k = 256 / star.z;
//			var x = Math.floor(star.x*k + this.origin.x);
//			var y = Math.floor(star.y*k + this.origin.y);
//				
//			if (x > 0 && x < this.canvas.width && y > 0 && y < this.canvas.height)				
//			{
//				pos = y * width + (x*4);
//				
//                //on traite les R, G, B, A des pixels du canvas
//				this.img_data.data[pos] = 255;
//				this.img_data.data[pos+1] = 255;
//				this.img_data.data[pos+2] = 255;	
//				this.img_data.data[pos+3] = ((this.max_depth-star.z)/this.max_depth) * 255; //permet de donner plus ou moins de transparence suivant la profondeur				
//			}
//		}	
//	
//		this.ctx.putImageData( this.img_data, 0, 0 );
//	};
	
    //retranscrire le fps
	this.draw_fps = function(delta_time)
	{
		this.fps_time += delta_time;
		this.fps_count++;
		
		// update the fps count every second
		if (this.fps_time > 1)
		{
			this.fps = Math.floor(this.fps_count/this.fps_time);
			this.fps_time = 0;
			this.fps_count = 0;
		}
		
		this.ctx.fillText("FPS: " + this.fps, 10, 25);		
	};

	this.set_origin = function(x, y)
	{
		this.origin.x = x;
		this.origin.y = y;
	};
	
	this.reset_origin = function()
	{
		this.origin.x = this.canvas.width/2;
		this.origin.y = this.canvas.height/2;
	};		

}