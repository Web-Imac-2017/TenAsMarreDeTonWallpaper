// lance une exception si la réponse HTTP a un code d'erreur différent de 2xx.
let handleHttpError = function(response){
    if (!response.ok) {
        throw Error('Erreur serveur lors de la connexion : ' + response.statusText);
    }
    return response;
};

let handleRequestError = function(response){    // response au format Object
    //console.log(response);
    if(!('returnCode' in response) || response.returnCode != 1){
        throw Error('returnMessage' in response ? response.returnMessage : "Aucun message d'erreur.");
    }
    return response;
};

export {handleHttpError, handleRequestError};