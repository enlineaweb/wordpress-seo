import stem from "../../../src/morphology/polish/stem";

const wordsToStem = [
	// Words with diminutive suffixes
	[ "stateczek", "stat" ],
	[ "maluszek", "mal" ],
	[ "ptaszek", "pt" ],
	[ "spacerek", "spacer" ],
	[ "ogonek", "ogon" ],
	[ "hamak", "ham" ],
	// Words with derivational noun suffixes
	[ "kolonizacja", "koloniz" ],
	[ "informacji", "inform" ],
	[ "dokumentach", "dokumen" ],
	[ "obieranie", "obier" ],
	[ "moczeniu", "mocz" ],
	[ "informatyka", "informat" ],
	[ "zebrania", "zebr" ],
	[ "osiągnięciu", "osiągni" ],
	[ "zebrania", "zebr" ],
	[ "agencja", "agenc" ],
	[ "zachęta", "zach" ],
	// Words with plural noun suffixes
	[ "ołówków", "ołówk" ],
	[ "ołówkom", "ołówk" ],
	[ "ołówkami", "ołówk" ],
	[ "ołówkach", "ołówk" ],
	// Words with verb suffixes
	[ "zjadłabym", "zjadł" ],
	[ "rysujesz", "rysuj" ],
	[ "zawijasz", "zawij" ],
	[ "zjadacie", "zjad" ],
	[ "przynieść", "przyn" ],
	[ "dopaść", "dop" ],
	[ "dostałem", "dost" ],
	[ "rozmawiamy", "rozmaw" ],
	[ "dotrzemy", "dotrz" ],
	[ "zjesz", "zje" ],
	[ "masz", "ma" ],
	[ "nieść", "nie" ],
	[ "paść", "pa" ],
	[ "stać", "st" ],
	[ "zjem", "zj" ],
	[ "znam", "zn" ],
	[ "znał", "zn" ],
	[ "doił", "do" ],
	[ "doić", "do" ],
	[ "dawaj", "dawa" ],
	// Words with adjective and adverb suffixes
	[ "piękniejszych", "piękniej" ],
	[ "najpiękniejszych", "piękniej" ],
	[ "piękniejsze", "piękniej" ],
	[ "najpiękniejsze", "piękniej" ],
	[ "kolorowa", "kolor" ],
	[ "niebezpieczny", "niebezp" ],
	[ "młodej", "młod" ],
	[ "pięknie", "piękn" ],
	[ "podejrzliwie", "podejrzliw" ],
	[ "szczerze", "szczer" ],
	// Words with general suffixes
	[ "konie", "kon" ],
	[ "konia", "kon" ],
	[ "stawu", "staw" ],
	[ "drogą", "drog" ],
	[ "drogi", "drog" ],
	[ "droga", "drog" ],
	[ "drogę", "drog" ],
	[ "cenny", "cenn" ],
	[ "drogę", "drog" ],
	[ "usiadł", "usiad" ],

	// Words with a matched suffix but which are below the word length limit
	[ "ptak", "ptak" ],
	[ "noga", "noga" ],
	[ "ssie", "ssie" ],
	[ "cenny", "cenn" ],
	[ "miłej", "miłej" ],
	[ "lwom", "lwom" ],

	// Words that are too short to look for suffixes in.
	[ "psy", "psy" ],
	[ "ta", "ta" ],
	[ "o", "o" ],

	// Long word with no suffixes
	[ "termometr", "termometr" ],

	// Adjective/adverb prefix does not get removed unless there was an adjective/adverb suffix
	[ "najadłam", "najad" ],
];

describe( "Test for stemming Polish words", () => {
	for ( let i = 0; i < wordsToStem.length; i++ ) {
		const wordToCheck = wordsToStem[ i ];
		it( "stems the word " + wordToCheck[ 0 ], () => {
			expect( stem( wordToCheck[ 0 ] ) ).toBe( wordToCheck[ 1 ] );
		} );
	}
} );
