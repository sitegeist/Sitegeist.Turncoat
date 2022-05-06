# Sitegeist.Turncoat

Theming for Neos CMS ... by separating the implementation of features from the rendering.

!!! This is an experimental approach to test the feasibility of the concept !!! 

The main idea is separation of packages providing features (nodetypes and integration) with packages providing 
themes (presentation) to render those features. A feature package can be whole site package with documents and 
menus or small package providing just a single specific nodetype.

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored
by our employer http://www.sitegeist.de.*

## Installation

Sitegeist.Turncoat is available via packagist and can be installed with the command `composer require sitegeist/turncoat`.

We use semantic-versioning so every breaking change will increase the major-version number.

## Usage

### Selecting a Theme

Themes are selected for the site of each document. To do this the 
`Sitegeist.Turncoat:Mixin.ThemeSelector` has to be added to the site document.

```yaml
'Vendor.Site:Document.Homepage':
  superTypes:
    'Sitegeist.Turncoat:Mixin.ThemeSelector': true
```

This adds the `theme` property to your site which allows to choose between all
installed theme packages.

## Features 

### Providing a Feature

Feature implement the nodetype and the integration layer but forward the rendering 
to the prototype `Sitegeist.Turncoat:ThemeRenderer` 

```
prototype(Vendor.Site:Content.Example) < prototype(Neos.Neos:ContentComponent) {
    renderer = Sitegeist.Turncoat:ThemeRenderer {
        feature = "Example"
        props {
            title = Neos.Fusion:Editable {
                property 'title'
            }
        }   
    }
}
```

### Implementing a Theme

Theme packages provide the rendering for all supported features and use the package-type `neos-theme`.

You can kickstart such a package with the command:
```
./flow package:create --package-type neos-theme Vendor.Theme
``` 

In the theme-package you have to create a file Settings.yaml with the following content
to ensure the fusion from your package will be loaded.

```
Neos:
  Neos:
    fusion:
      autoInclude:
        Vendor.Theme: true
```

To include all features from you fusion folder you should add the following line to your root.fusion

```neosfusion
include: ./**/*.fusion
```

Now you can start implementing features.

### Implementing a Feature in a Theme

Each feature is implemented by providing a prototype in the `Feature` namespace
of the theme-package.

```neosfusion
prototype(Vendor.Theme:Feature.Example) < prototype(Neos.Fusion:Component) {
    title = null    
    renderer = afx`
        <h1 style="font-family:ComicSans,sans-serif; color:red;">
            {props.title}
        </h1> 
    `
}
```

### Providing a fallback rendering

Features can provide a default rendering that can be overridden by themes
to do so a fallback package can be defined that provides a rendering. It makes sense 
that this fallback either is inside the package providing the feature or in 
a package this depends on.

```
prototype(Vendor.Site:Content.Example) < prototype(Neos.Neos:ContentComponent) {
    renderer = Sitegeist.Turncoat:ThemeRenderer {
        feature = "Example"
        fallback = "Vendor.Theme"
        props {
            ...
        }   
    }
}

prototype(Vendor.Theme:Feature.Example) < prototype(Neos.Fusion:Component) {
    title = null    
    renderer = afx`
        <h1 style="font-family:Times,serif; color:green;">
            {props.title}
        </h1> 
    `
}
```

## Contribution

We will gladly accept contributions. Please send us pull requests.
