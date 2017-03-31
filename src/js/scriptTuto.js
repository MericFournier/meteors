var citations = ["Bienvenue sur le site SpaceImpact !",
                 "Grâce au marqueur de localisation orange, tu peux repérer ta position sur la planète.", 
                 "En parcourant le globe terrestre tu pourras voir les nombreux impacts de météorites qui ont touché la terre à partir de 4 janvier 1998. Clique sur un point coloré pour voir les caractéristiques de la météorite.", 
                 "Tu peux maintenant modifier sa masse, sa vélocité ou sa force d’impact et constater le nombre de victime potentiel qu’aurait pu faire la météorite.",
                 "Compare maintenant le nombre obtenu avec les chiffres d’autres accidents mortels de 2016."
                ];


// init var
var afficheMessage;
var show_text;
var messageText = document.querySelector(".messagesTuto p");

var chain = citations[0];
var nb_car = chain.length;
var array = chain.split("");
var text = new Array;
var txt = '';
var nb_msg = nb_car - 1;
var text_max_lenght = citations.length;
var current_text = 0;

for (i=0; i < nb_car; i++) {
	text[i] = txt+array[i];
	var txt = text[i];
}

var actual_text = 0;
// change message 
function changeMessage() {
	messageText.innerHTML = text[actual_text];
	actual_text++;
	if(actual_text >= text.length)
	{
		actual_text = nb_msg;
		clearInterval(afficheMessage);
		if(current_text < citations.length) {
            var understood = document.querySelector(".messagesTuto .understood");
            understood.addEventListener('click', function(){
               requestAnimationFrame(textAnime);
            })
		}
        else{
            requestAnimationFrame(textAnime);
            current_text = 0;
        }
	}
}

// display letter one by one
function randCitation(){
	chain = citations[current_text];
	nb_car = chain.length;
	array = chain.split("");
	text = new Array;
	txt = '';
	nb_msg = nb_car - 1;

	for (i=0; i < nb_car; i++) {
		text[i] = txt+array[i];
		txt = text[i];
	}


	actual_text = 0;

	afficheMessage = setInterval("changeMessage()",80);

}

// launch animation

function textAnime(){
	randCitation();
	current_text++;
}

textAnime();
