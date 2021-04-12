# theoldbattledome
Re-creation of the original Neopets Battledome

Notes on database notation:
A number of 1000+ indicates a percent-based effect. (Used for defense, heal, drain)
Ex. "light,1100" indicates 100% light defense
  "water,1050" indicates 50% water defense
 
General format for fixed attack/defense icons:
icon1,amount:icon2,amount:icon3,amount
Ex. air,2:fire,2

For variable icon amounts, 'amount' is given as a group of numbers indicating range+distrubution:
Normal distribution: icon,(min;max;increment;avg;std)
  Ex. anagram sword: phys,(1.25;9;0.1;1;2.8)
    gsword: air,(0;15;3;3;3.5)

Equal distribution: icon,(min;max;incrememnt;-1) (The -1 flags it as a normal distribution)
Ex. Battle Plunger: water,(2;7;1;-1)

The 'Useage' column indicates breakability from 0 - 4
0 - multiuse (never breaks)
1 - chance of breaking on each use, and maybe break-on-success
  Ex. Pirate Captains Hat has a 50% chance to break on each use (entered in db as "1,0.5")
2 - once per battle
3 - perma-break on success or by random roll on each use
4 - single use
