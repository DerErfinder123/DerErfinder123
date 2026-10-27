# Installiert eine lokale Homebrew-Version und PHP auf macOS.
mkdir -p ~/.homebrew

# Laedt Homebrew herunter und entpackt es in das Benutzerverzeichnis.
curl -L https://github.com/Homebrew/brew/tarball/master | tar xz --strip 1 -C ~/.homebrew

# Macht Homebrew-Befehle in neuen und aktuellen Shells verfuegbar.
echo 'export PATH="$HOME/.homebrew/bin:$PATH"' >> ~/.zshrc

source ~/.zshrc

chmod -R go-w ~/.homebrew


brew install php

/opt/homebrew