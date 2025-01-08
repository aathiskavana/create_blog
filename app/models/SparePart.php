// app/Models/SparePart.php

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price',
        'stock',
        // Add other fields as needed
    ];
}