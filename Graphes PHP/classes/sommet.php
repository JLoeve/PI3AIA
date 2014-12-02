<?PHP
class Sommet
{
	public $id;
	public $voyage;
	public $voisins = Array();
	public $parcouru = false;	
	
	function __construct($voyage_init)
	{
		$this->voyage = $voyage_init;
		$this->parcouru = false;
	}
	
	function get_id()
	{
		return $this->id;
	}
	
	function set_id($id)
	{
		$this->id = $id;
	}
	
	function get_liste_voisins()
	{
		return $this->voisins;
	}
	
	function ajouter_voisin($sommet)
	{
		//$temps = $tab_terminus[$this->voyage["tarr"]][$sommet->voyage["tdep"]];
		$this->voisins[] = Array($sommet->get_id(), 12);
	}
	
	function supprimer_voisin($sommet)
	{
		array_splice($voisins, $sommet->get_id(), 1); // efface un élément
	}
}	
?>	