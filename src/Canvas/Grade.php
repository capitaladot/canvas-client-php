<?php

namespace Canvas;

class CanvasGrade extends CanvasModel
{
	public $grade;
	public $student;
	public $course;
	
	public function __construct($grade, CanvasUser $student, CanvasCourse $course)
	{
		$this->grade 	= $grade;
		$this->student 	= $student;
		$this->course 	= $course;
	}
}