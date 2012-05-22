
<h1>Ajouter une collection de données</h1>

<form action="/collections/ajouter.html?next=<?=rawurlencode(NE($_REQUEST,'next'))?>" class="login" method="post">
    
    <?php if($this->hasErrors()) { ?>
    <div class="error">
    <p>Votre formulaire contient des erreurs:</p>
	<?=$this->displayErrors(true,array('alwaysList'=>true))?>
    </div>
    <?php } ?>
    
    <div class="field">
    	<label>Nom :</label>
        <input type="text" name="email" class="text" value="<?=NE($_POST,'email')?>" />
        <div class="clear"></div>
    </div>
    
    <div class="field">
    	<label>Description :</label>
        <textarea name="description"><?=NE($_POST,'email')?></textarea>
        <div class="clear"></div>
    </div>
    <div class="spacer-small"></div>
	
    <h4>Colonnes</h4>
    <?php
	
		$cols = array(array());
	
	?>
    <ul class="cols">
    	<?php
        
		foreach($cols as $col) {
			
			?>
            <li>
            	<div class="field name">
                    <label>Nom de la colonne :</label>
                    <div><input type="text" name="cols_name[]" class="text" value="<?=NE($col,'name')?>" /></div>
                    <div class="mt10">
                    <label class="neutral">
                    	<input type="hidden" name="cols_required[]" value="<?=NE($col,'required',0)?>" />
                        <input type="checkbox" class="required" value="1" <?=((int)NE($col,'required',0) == 1 ? 'checked="checked"':'')?> /> 
                        Cette colonne est obligatoire
                    </label>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="field description">
                    <label>Description :</label>
                    <textarea name="cols_description[]"><?=NE($col,'description')?></textarea>
                    <div class="clear"></div>
                </div>
                
                <div class="field required">
                	<label>&nbsp;</label>
                    <div class="clear"></div>
                </div>
                
                <div class="clear"></div>
            
            </li>
            <?php
			
		}
			
		?>
    </ul>
    
    <div class="spacer-small"></div>
    
    <div class="mt10">
    	<a href="#" class="button">+ Ajouter une colonne</a>
    </div>
    
    <div class="hr"></div>
    
    <div class="spacer-small"></div>
    
    <div class="buttons" align="right">
    	<a href="<?=NE($_REQUEST,'next','/collections')?>">Annuler</a> 
    	<button type="submit">Enregistrer et ajouter les données &raquo;</button>
    </div>
</form>