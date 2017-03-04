// lance une exception si la réponse HTTP a un code d'erreur différent de 2xx.
let handleHttpError = function(response){
    if (!response.ok) { throw Error(response.statusText); }
    return response;
}

export {handleHttpError};