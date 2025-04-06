@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Ajouter un produit</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
            <button type="button" id="generate-description" class="btn btn-secondary mt-2">Générer par IA</button>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantité</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control">
            <button type="button" id="generate-image" class="btn btn-secondary mt-2">Générer par IA</button>
            <input type="hidden" name="image_url" id="image_url">
        </div>
        
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script>
    // Générer la description avec l'API Gemini
    document.getElementById('generate-description').addEventListener('click', async () => {
        const name = document.getElementById('name').value;
        if (!name) {
            alert('Veuillez entrer un nom de produit.');
            return;
        }

        try {
            const response = await fetch("{{ route('admin.products.generateDescription') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ name })
            });

            const data = await response.json();
            if (data.description) {
                document.getElementById('description').value = data.description;
            } else {
                alert('Erreur lors de la génération de la description.');
            }
        } catch (error) {
            console.error(error);
            alert('Erreur lors de la génération de la description.');
        }
    });

    // Générer l'image avec l'API Hugging Face
    document.getElementById('generate-image').addEventListener('click', async () => {
        const description = document.getElementById('description').value;
        
        if (!description) {
            alert('Veuillez entrer une description du produit.');
            return;
        }

        try {
            const response = await fetch("{{ route('admin.products.generateImage') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ description })
            });

            const data = await response.json();
            if (data.image_url) {
                document.getElementById('image_url').value = data.image_url;
                alert('Image générée avec succès. Vous pouvez maintenant ajouter le produit.');
            } else if (data.error) {
                alert('Erreur : ' + data.error);
            } else {
                alert('Erreur inconnue lors de la génération de l\'image.');
            }
        } catch (error) {
            console.error(error);
            alert('Erreur lors de la génération de l\'image.');
        }
    });

    // Convertir une URL en fichier
    async function urlToFile(url, filename) {
        const response = await fetch(url);
        const blob = await response.blob();
        return new File([blob], filename, { type: blob.type });
    }
</script>
@endsection
