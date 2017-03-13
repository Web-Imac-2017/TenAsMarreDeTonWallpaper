// http://stackoverflow.com/a/14794066
let isInt = function(value) {
  return !isNaN(value) &&
         parseInt(Number(value)) == value &&
         !isNaN(parseInt(value, 10));
}
export {isInt};
