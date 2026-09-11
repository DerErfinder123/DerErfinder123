mkdir -p ~/.homebrew


curl -L https://github.com/Homebrew/brew/tarball/master | tar xz --strip 1 -C ~/.homebrew

echo 'export PATH="$HOME/.homebrew/bin:$PATH"' >> ~/.zshrc

source ~/.zshrc

chmod -R go-w ~/.homebrew


brew install php

/opt/homebrew