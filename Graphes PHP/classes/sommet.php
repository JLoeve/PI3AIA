<?PHP
class sommet
{
	public $voyage;
	public $voisins = Array();
	public $etat = "";
	
	function __construct($voyage_init)
	{
		$this->voyage = $voyage_init;
	}
	
	function ajouter_voisin($sommet, $tab_terminus)
	{
		$temps = $tab_terminus[$this->voyage["t_arr"]][$sommet->voyage["t_dep"]];
		$this->voisins[] = $sommet;
	}
	
	function supprimer_voisin($sommet)
	{
		$cpt = 0;
		foreach($voisins as $voisin)
		{
			if(		$sommet->voyage["ligne"] == $voisin->voyage["ligne"]
				&& 	$sommet->voyage["sens"] == $voisin->voyage["sens"]
				&& 	$sommet->voyage["voyage"] == $voisin->voyage["voyage"])
			{
				array_splice($voisins, $cpt, 1); // efface un élément à partir de cpt
			}
		$cpt ++;
		}
	}
	
	function get_liste_voisins()
	{
		return $this->voisins;
	}
}	
?>	