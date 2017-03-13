const rapports = [
    {
        l:16, h:9,
        res:[
            {l:3840,h:2160},
            {l:2560,h:1440},
            {l:1920,h:1080},
            {l:1600,h:900},
            {l:1366,h:768},
            {l:1280,h:720},
            {l:1024,h:576}
        ]
    },
    {
        l:4, h:3,
        res:[
            {l:2048,h:1536},
            {l:1600,h:1200},
            {l:1440,h:1080},
            {l:1400,h:1050},
            {l:1280,h:960},
            {l:1024,h:768},
            {l:800,h:600},
            {l:640,h:480}
        ]
    },
    {
        l:8, h:5,
        res:[
            {l:2560,h:1600},
            {l:1920,h:1200},
            {l:1440,h:900},
            {l:1280,h:800}
        ]
    }
    // Plus de rapports ici...
    // 5:4, 3:2, 5:3, 17:9
];

const rapport_chercher = function(ecran) {
    for (var r of rapports)
        if (Math.abs(r.l/r.h - ecran.l/ecran.h) < 0.1)
            return r;
    return null;
};
// XXX documenter ce monstre
const rapport_filtrer = function(p) {
    let res = {l:p.rapport.l, h:p.rapport.h, res:[]};
    for(var i=0, j=0 ; i<p.rapport.res.length && j<p.limite ; ++i) {
        let candidate = p.rapport.res[i];
        if(candidate.l <= p.wpp.l   && candidate.h <= p.wpp.h
        && candidate.l != p.ecran.l && candidate.h != p.ecran.h) {
            res.res[j] = candidate;
            ++j;
        }
    }
    return res;
}

export {rapports, rapport_chercher, rapport_filtrer};
