<?php

class EchoClassVisitor implements SL_IO_IClassVisitor{
	
		public function visit(SplFileInfo $info, $name, $type){
			echo '<div>Class : ' . $name . ' Type : ' . $type .' Path : ' . $info->getPathname() .'</div>'; 
		}
}

class EchoAnnotationVisitor implements SL_IO_IAnnotationVisitor{
	
	public function visit(ReflectionAnnotatedClass $annotationInfo){
		echo '<div> Class : ' . $annotationInfo->getName() . '</div>';
		echo '<div> Annotations : <br>';
		foreach($annotationInfo->getAnnotations() as $annotation){
			print_r($annotation);
		}
		echo '</div><br>';
	}
	
}


class OVP_Test_Plugin implements IPlugin {
	
	public function intializeAdmin(){
		
	}
	
	public function intializeClient(){
		add_shortcode('listClasses', 'OVP_Test_Plugin::listClasses');
		add_shortcode('listAnnotations', 'OVP_Test_Plugin::listAnnotations');
	}
	
	public static function listClasses(){
		$classReader = new OV_IO_ClassReader(new EchoClassVisitor());
		$classReader->read(Constants::getRootDir());
	}
	
	
	public static function listAnnotations(){
		$annReader = new OV_IO_AnnotationReader(new EchoAnnotationVisitor());
		$annReader->read(Constants::getRootDir(),true,true);
	}
	
	
}

?>